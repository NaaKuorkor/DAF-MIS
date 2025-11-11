<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{


    public function createStaff(Request $request)
    {

        try {

            //Validate data
            $staffData = $request->validate([
                'fname' => 'string|required|max:50',
                'mname' => 'nullable|string|max:50',
                'lname' => 'string|required|max:50',
                'email' => 'email|required',
                'gender' => 'string|required|max:1',
                'position' => 'string|required|max:100',
                'phone' => 'string|required|max:15',
                'password' => 'required|string|min:6',
                'user_type' => 'required|string',
            ]);



            DB::transaction(function () use ($staffData) {


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
                    'usertype' => $staffData['user_type'],
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
                    'position' => $staffData['position'],

                ]);

                DB::statement('UNLOCK TABLES');
            });

            return redirect()->route('staff.dashboard')->with('success', "New admin created successfully!");
        } catch (\Exception $e) {
            Log::error('Staff registration failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            DB::statement('UNLOCK TABLES');

            return back()->withErrors('Registration failed');
        }
    }


    public function dashboardMenu()
    {
        $user = auth()->user();

        $modules = $user->modules()->wherePivot('mod_read', 1)
            ->orderBy('mod_position', 'asc')->get();

        return view('staff.dashboard', compact('modules'));
    }


    //public function deleteStaff() {}

    //public function viewUsers() {}

    //public function deleteUser() {}

    //public function viewStaff() {}
}
