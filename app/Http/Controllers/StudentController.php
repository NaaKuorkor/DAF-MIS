<?php

namespace App\Http\Controllers;

use App\Models\TblCourseRegistration;
use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StudentController extends Controller
{


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
                'password' => 'required|confirmed|min:6',
                'gender' => 'required|string',
                'residence' => 'required|string',
                'referral' => 'required|string',
                'employment_status' => 'required|string',
                'certificate' => 'required|string',
                'course' => 'required|string'
            ]);

            //Create student to be inserted into the databases

            DB::transaction(function () use ($validateData, &$user) {


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
                    $studentid = 'S0000000001';
                } else {
                    $newNum = $studentCount + 1;
                    $studentid = 'S' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
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
                    'password' => Hash::make($validateData['password']),
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
                    'residence' => $validateData['residence'],
                    'referral' => $validateData['referral'],
                    'employment_status' => $validateData['employment_status'],
                    'certificate' => $validateData['certificate'],
                ]);

                TblCourseRegistration::create([
                    'transid' => $transid,
                    'studentid' => $student->studentid,
                    'course_id' => $validateData['course'],
                    'createuser' => $validateData['email'],
                    'modifyuser' => $validateData['email'],
                ]);


                DB::statement('UNLOCK TABLES');
            });

            $user->sendEmailVerificationNotification();
            Auth::login($user);



            return redirect()->route('verification.notice');
        } catch (\Exception $e) {
            Log::error('Registration Failed!', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            DB::statement('UNLOCK TABLES');

            return back()->withErrors("Registration failed. Try again")->withInput();
        }
    }
}
