<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
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
            'residence' => 'required|string',

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
                'residence' => $validateData['residence'],
            ]);



        });


        return redirect()->route('student.dashboard')->with('success', 'Registration successful!');
    }

    public function showLoginForm() {
        return view('student.login');
    }


    public function showDashboard () {
        return view('student.dashboard');
    }
}
