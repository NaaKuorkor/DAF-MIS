<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function showStaffLogin()
    {
        return view("staff.login");
    }


    public function showStaffDashboard()
    {
        return view("staff.dashboard");
    }

    public function showRegisterForm()
    {
        return view('student.register');
        //shows the register view
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
