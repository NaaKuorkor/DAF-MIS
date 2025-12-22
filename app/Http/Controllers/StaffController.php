<?php

namespace App\Http\Controllers;

use App\Mail\Verify;
use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStaff;
use App\Models\TblUserModulePriviledges;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class StaffController extends Controller
{
    public function createStaff(Request $request, SmsService $sms)
    {

        try {

            //Validate data
            $staffData = $request->validate([
                'fname' => 'string|required|max:50',
                'mname' => 'nullable|string|max:50',
                'lname' => 'string|required|max:50',
                'email' => 'email|required',
                'gender' => 'string|required|max:1',
                'age' => 'integer|required',
                'position' => 'string|required|max:100',
                'phone' => 'string|required|max:15',
                'password' => 'required|string|min:6',
                'residence' => 'required|string',
                'department' => 'required|string'
            ]);



            DB::transaction(function () use ($staffData, &$user, &$staff) {


                $idCount =  DB::table('tbluser')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');
                $userid = null;

                if ($idCount === 0) {
                    $userid = 'U0000000001';
                } else {
                    $newNum = $idCount + 1;

                    $userid = 'U' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }


                $staffCount =   DB::table('tblstaff')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $staffid = null;

                if ($staffCount === 0) {
                    $staffid = 'STF0000000001';
                } else {
                    $newNum = $staffCount + 1;
                    $staffid = 'STF' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }


                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $staffData['email'],
                    'password' => Hash::make($staffData['password']),
                    'phone' => $staffData['phone'],
                    'usertype' => 'STA',
                    'createdate' => now(),
                    'createuser' => auth()->user()->email ?? 'system',
                    'modifydate' => now(),
                    'modifyuser' => auth()->user()->email ?? 'system',

                ]);

                $staff = TblStaff::create([
                    'userid' => $user->userid,
                    'staffid' => $staffid,
                    'fname' => $staffData['fname'],
                    'mname' => $staffData['mname'] ?? null,
                    'lname' => $staffData['lname'],
                    'gender' => $staffData['gender'],
                    'age' => $staffData['age'],
                    'residence' => $staffData['age'],
                    'position' => $staffData['position'],
                    'department' => $staffData['department']

                ]);

                $modulePriviledges = [
                    [
                        //Overview
                        'userid' => $user->userid,
                        'modid' => 'MOD001',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        //Courses
                        'userid' => $user->userid,
                        'modid' => 'MOD004',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        //Tasks
                        'userid' => $user->userid,
                        'modid' => 'MOD006',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,
                    ],
                    [
                        //Account
                        'userid' => $user->userid,
                        'modid' => 'MOD007',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,

                    ]
                ];

                foreach ($modulePriviledges as $priviledges) {
                    TblUserModulePriviledges::create([
                        'userid' => $priviledges['userid'],
                        'modid' => $priviledges['modid'],
                        'mod_create' => $priviledges['mod_create'],
                        'mod_read' => $priviledges['mod_read'],
                        'mod_update' => $priviledges['mod_update'],
                        'mod_delete' => $priviledges['mod_delete'],
                    ]);
                }
            });


            $verificationLink = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->userid, 'hash' => sha1($user->email)]
            );

            //Send mail with verification link.
            Mail::to($user->email)->send(new Verify($staff, $verificationLink));


            //Change phone format to have the country code
            $phone = preg_replace('/^0/', '233', $user->phone);
            //Interpolate name, email and password within message
            $message = "Thank you {$staff->fname} for registering to be a part of DAF.\n Your login credentials are as follows.\n
                Email : {$user->email}\n
                Password : {$user->phone}\n
                You have the liberty to change your password once you login.\n
                Enjoy your time with us!
                ";

            $sms->send($phone, $message);


            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully!'
                ]);
            } else {
                return back()->with('Success', 'Account created successfully!');
            }
        } catch (\Exception $e) {
            Log::error('Staff registration failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            DB::statement('UNLOCK TABLES');

            return back()->withErrors('Registration failed');
        }
    }

    public function staffProfile()
    {

        $user = Auth::user()->load('staff');

        return view('components.my-staff-account', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        try {

            $user = Auth::user();

            $fields = $request->validate([
                'fname' => 'string|required|max:50',
                'mname' => 'nullable|string|max:50',
                'lname' => 'string|required|max:50',
                'email' => 'email|required',
                'gender' => 'string|required|max:1',
                'age' => 'integer|required',
                'position' => 'string|required|max:100',
                'phone' => 'string|required|max:15',
                'residence' => 'required|string',
                'department' => 'required|string'
            ]);


            DB::transaction(function () use ($user, $fields) {
                //Insert in usertable
                $user->update([
                    'email' => $fields['email'],
                    'phone' => $fields['phone'],
                ]);

                //Insert in staff table
                $user->staff->update([
                    'fname' => $fields['fname'],
                    'mname' => $fields['mname'],
                    'lname' => $fields['lname'],
                    'gender' => $fields['gender'],
                    'age' => $fields['age'],
                    'position' => $fields['position'],
                    'residence' => $fields['residence'],
                    'department' => $fields['department'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Updates failed'
            ]);
        }
    }
}
