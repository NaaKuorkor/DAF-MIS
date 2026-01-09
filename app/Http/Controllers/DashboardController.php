<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TblStaff;
use App\Models\TblCourse;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function fetchModules()
    {
        $user = Auth::user();


        //Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $modules = $user->modules()->wherePivot('mod_read', 1)->get();

        return response()->json($modules);
    }

    // app/Http/Controllers/DashboardController.php

    // app/Http/Controllers/DashboardController.php

public function getContent($route)
{
    // Handle overview specially - needs $cards data
    if ($route === '/staff/overview') {
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

        return response(view('components.staffOverview', compact('cards'))->render());
    }

    // Map routes to their corresponding component views
    $routeViewMap = [
        '/staff/student-info' => 'components.student_mngt',
        '/staff/staff-info' => 'components.staff_mngt',
        '/staff/courses' => 'components.course_mngt',
        '/staff/tasks' => 'components.task_mngt',
        '/staff/cohorts' => 'components.cohort_mngt',
        '/staff/myAccount' => 'components.my-staff-account',
        '/staff/announcements' => 'components.announcements',
        '/course-cohort' => 'components.student-courses',
        '/myProfile' => 'components.my-student-account',
    ];

    // Get the view name from the map, default to overview
    $viewName = $routeViewMap[$route] ?? 'components.staffOverview';
    
    return response(view($viewName)->render());
}

    public function overviewContent()
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

        $html = view('components.staffOverview', compact('cards'));

        return response($html);
    }
}
