<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function showRegisterForm () {
        return view('student.register');
        //shows the register view
    }

    public function register (Request $request) {

        //Form validation
        $validateData = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'sname' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'password' =>'required|confirmed|min:6',
            'gender' => 'required|string',
            'cohort_id' => 'required|string',
            'course_id' => 'required|string',
        ]);

        //Create student to be inserted into the databases

        DB::transaction(function() use($validateData){

            $user = TblUser::create([
                'email' => $validateData['email'],
                'phone' => $validateData['phone'],
                'password' => Hash::make($validateData['password']),
                'user_type' => 'STU',
                'deleted' => 0,
                'createuser' => auth()->user()->email ?? 'system',
                'modifyuser' => auth()-> user()->email ?? 'system',
            ]);

            $student = TblStudent::create([
                'user_id' => $user->user_id,
                'fname' => $validateData['fname'],
                'mname' => $validateData['mname'] ?? null,
                'sname' => $validateData['sname'],
                'gender' => $validateData['gender'],
            ]);



        });


        return redirect()->route('student.dashboard')->with('success', 'Registration successful!');
    }

    public function showLoginForm() {
        return view('student.login');
    }

    public function login (Request $request) {
        //Validate data
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt($data)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');

        }return back()->withErrors([
            'email' => "Incorrect email or password used"
        ])->onlyInput('email');



    }

    public function logout (Request $request) {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();//Generates new csrf token

        return redirect()->route('login.form')->with('success', "Logged out successfully.");
    }

    public function showDashboard () {
        return view('dashboard');
    }
}
