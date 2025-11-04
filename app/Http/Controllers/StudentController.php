<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StudentController extends Controller
{
    public function showRegisterForm()
    {
        return view('student.register');
        //shows the register view
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
                'password' => 'required|confirmed|min:6',
                'gender' => 'required|string',
                'residence' => 'required|string',
                'referral' => 'required|string',
                'employment_status' => 'required|string',
                'certificate' => 'required|string',

            ]);

            //Create student to be inserted into the databases

            $idCount = TblUser::count();
            $userid = null;

            if ($idCount === 0) {
                $userid = 'U0000000001';
            } else {
                $newNum = $idCount + 1;
                $userid = 'U' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
            }




            $studentCount = TblStudent::count();
            $studentid = null;

            if ($studentCount === 0) {
                $studentid = 'S0000000001';
            } else {
                $newNum = $studentCount + 1;
                $studentid = 'S' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
            }





            DB::transaction(function () use ($validateData, $userid, $studentid) {

                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $validateData['email'],
                    'phone' => $validateData['phone'],
                    'password' => Hash::make($validateData['password']),
                    'user_type' => 'STU',
                    'deleted' => 0,
                    'createuser' => auth()->user()->email ?? 'system',
                    'modifyuser' => auth()->user()->email ?? 'system',
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
            });


            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            Log::error('Registration Failed!', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors("Registration failed. Try again")->withInput();
        }
    }

    public function showLoginForm()
    {
        return view('student.login');
    }


    public function showDashboard()
    {
        return view('student.dashboard');
    }
}
