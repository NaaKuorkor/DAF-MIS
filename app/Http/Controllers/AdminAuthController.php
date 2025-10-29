<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showStaffLogin(){
        return view("staff.login");
    }

    public function staffLogin(Request $request) {
        //Validate data
        $staffData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        //Check if data is in database
        $staff = TblUser::where("email", $staffData['email'])->first();

        if (!$staff || !Hash::check($staffData['password'], $staff->password)) {
            return back()->withErrors(['email' => "Incorrect credentials."])->onlyInput('email');
        }

        //Login with the staff guard and regenerate session
        Auth::guard('staff')->login($staff);
        $request->session()->regenerate();

        return redirect()->route('staff.dashboard')->with('success', 'Successfully logged in!');
    }

    public function showStaffDashboard() {
        return view("staff.dashboard");
    }

    public function createStaff(Request $request) {

        //Validate data
        $staffData = $request->validate([
            'fname' => 'string|required|max:50',
            'mname' => 'nullable|string|max:50',
            'lname' => 'string|required|max:50',
            'gender' => 'string|required|10',
            'position' => 'string|required|max:100',
            'phone' => 'string|required|max:15',
            'password' => 'required|string|min:6',
            'department' => 'required|string|max:100',
            'user_type' => 'required|string',
        ]);

        $user = TblUser::create([
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
            'fname' => $staffData['fname'],
            'mname' => $staffData['mname'] ?? null,
            'lname' => $staffData['lname'],
            'gender' => $staffData['gender'],
            'position' => $staffData['position'],
            'department' => $staffData['department'],
        ]);

        return redirect()->route('staff.dashboard')->with('success', "New admin created successfully!");

    }

    public function logout (Request $request) {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login.form')->with('success', 'Logged out successfully!');

    }

    public function deleteStaff(){

    }

    public function viewUsers() {

    }

    public function deleteUser() {

    }

    public function viewStaff() {

    }
}
