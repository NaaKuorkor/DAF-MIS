<?php

namespace App\Http\Controllers;

use App\Models\TblCourseRegistration;
use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use App\Models\TblUserModulePriviledges;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\Verify;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function sendSMS($phone, $message)
    {
        //Add headers for arkesel
        $response = Http::withHeaders([
            'api-key' => env('ARKESEL_SMS_API_KEY'),
        ])->post(env('ARKESEL_SMS_URL'), [
            'sender' => env('ARKESEL_SMS_SENDER_ID'),
            'message' => $message,
            'recipients' => $phone
        ]);

        return $response->json();
    }

    public function register(Request $request)
    {
        try {

            //dd($request->all());
            //Form validation
            $validateData = $request->validate([
                'fname' => 'required|string|max:255',
                'mname' => 'nullable|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'gender' => 'required|string',
                'age' => 'required|integer',
                'residence' => 'required|string',
                'referral' => 'required|string',
                'employment_status' => 'required|string',
                'certificate' => 'required|string',
                'course' => 'required|string'
            ]);

            $password = $request->password ? Hash::make($request->password) : Hash::make($request->phone);

            //Create student to be inserted into the databases

            DB::transaction(function () use ($validateData, &$user, &$student, $password) {


                $idCount =  DB::table('tbluser')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $userid = null;

                if ($idCount === 0) {
                    $userid = 'U0000000001';
                } else {
                    $newNum = $idCount + 1;
                    $userid = 'U' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }




                $studentCount =  DB::table('tblstudent')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $studentid = null;

                if ($studentCount === 0) {
                    $studentid = 'STU0000000001';
                } else {
                    $newNum = $studentCount + 1;
                    $studentid = 'STU' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }

                $transCount =  DB::table('tblcourse_registration')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $transid = null;

                if ($transCount === 0) {
                    $transid = 'TS0000000001';
                } else {
                    $newNum = $transCount + 1;
                    $transid = 'TS' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }




                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $validateData['email'],
                    'phone' => $validateData['phone'],
                    'password' => $password,
                    'user_type' => 'STU',
                    'deleted' => 0,
                    'createuser' => $validateData['email'],
                    'modifyuser' => $validateData['email'],
                ]);




                $student = TblStudent::create([
                    'userid' => $user->userid,
                    'studentid' => $studentid,
                    'fname' => $validateData['fname'],
                    'mname' => $validateData['mname'] ?? null,
                    'lname' => $validateData['lname'],
                    'gender' => $validateData['gender'],
                    'age' => $validateData['age'],
                    'residence' => $validateData['residence'],
                    'referral' => $validateData['referral'],
                    'employment_status' => $validateData['employment_status'],
                    'certificate' => $validateData['certificate'],
                ]);

                TblCourseRegistration::create([
                    'studentid' => $student->studentid,
                    'course_id' => $validateData['course'],
                    'createuser' => $validateData['email'],
                    'modifyuser' => $validateData['email'],
                ]);


                $modulePriviledges = [
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD001',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD004',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD007',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,

                    ]
                ];

                foreach ($modulePriviledges as $p) {
                    TblUserModulePriviledges::create([
                        'userid' => $p['userid'],
                        'modid' => $p['modid'],
                        'mod_create' => $p['mod_create'],
                        'mod_read' => $p['mod_read'],
                        'mod_update' => $p['mod_update'],
                        'mod_delete' => $p['mod_delete'],
                        'createuser' => 'system',
                        'modifyuser' => 'system',
                    ]);
                }
            });

            //Create verification link to be sent to user
            $verificationLink = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->userid, 'hash' => sha1($user->email)]
            );

            //Send mail with verification link.
            Mail::to($user->email)->send(new Verify($student, $verificationLink));

            if (!$request->password) {
                //Change phone format to have the country code
                $phone = preg_replace('/^0/', '233', $user->phone);
                //Interpolate name, email and password within message
                $message = "Thank you {$student->fname} for registering to be a part of DAF.\n Your login credentials are as follows.\n
                Email : {$user->email}\n
                Password : {$request->phone}\n
                You have the liberty to change your password once you login.\n
                Enjoy your time with us!
                ";

                $response = $this->sendSMS($phone, $message);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully!'
                ]);
            } else {
                return back()->with('Success', 'Account created successfully!');
            }
        } catch (\Exception $e) {
            Log::error('Registration Failed!', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Registration failed. Try again"
                ]);
            } else {
                return back()->withErrors("Registration failed. Try again")->withInput();
            }
        }
    }



    public function showVerifySuccess(Request $request, $id, $hash)
    {

        //Retrieves student from database
        $user = TblUser::where('userid', $id)->firstOrFail();

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect('/register')->with('error', 'Invalid verification link.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('success', 'Email verified!');
    }
}
