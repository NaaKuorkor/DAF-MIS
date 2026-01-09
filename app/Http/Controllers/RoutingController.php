<?php

namespace App\Http\Controllers;

use App\Models\TblCourse;
use App\Models\TblStaff;
use App\Models\TblStudent;
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
        $students = TblStudent::count();
        $staff = TblStaff::count();
        $courses = TblCourse::count();

        $cards = [
            [
                'title' => 'Total Students',
                'value' => $students,
            ],
            [
                'title' => 'Total Staff',
                'value' => $staff,
            ],
            [
                'title' => 'Total Courses',
                'value' => $courses,
            ],
        ];

        return view('components.staffOverview', compact('cards'));
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

    public function showAnnouncements()
    {
        return view('components.announcements');
    }
}
