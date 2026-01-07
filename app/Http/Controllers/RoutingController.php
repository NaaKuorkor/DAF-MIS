<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

    public function showVerifyEmail()
    {
        return view('verifyEmail');
    }

    public function showStaffOverview()
    {
        return view('components.staffOverview');
    }

    public function showStudentMngt()
    {
        return view('components.student_mngt');
    }

    public function showStaffMngt()
    {
        return view('components.staff_mngt');
    }

    public function showCourseMngt()
    {
        return view('components.course_mngt');
    }

    public function showTaskMngt()
    {
        return view('components.task_mngt');
    }

    public function showStaffProfile()
    {
        return view('components.my-staff-account');
    }

    public function showStudentProfile()
    {
        return view('components.my-student-account');
    }

    public function showCohortMngt()
    {
        return view('components.cohort_mngt');
    }

    public function course_cohort()
    {
        return view('components.student-courses');
    }
}
