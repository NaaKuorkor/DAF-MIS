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
        // Handle overview specially - needs $cards and $chartData data
        if ($route === '/staff/overview') {
            $data = $this->getOverviewData();
            return response(view('components.staffOverview', $data)->render());
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
            '/announcements' => 'components.student-announcements',
        ];

        // Get the view name from the map, default to overview
        $viewName = $routeViewMap[$route] ?? 'components.staffOverview';
        
        return response(view($viewName)->render());
    }

    public function overviewContent()
    {
        $data = $this->getOverviewData();
        return response(view('components.staffOverview', $data));
    }

    private function getOverviewData()
    {
        $students = TblStudent::whereHas('user', function($query) {
            $query->where('deleted', '0');
        })->count();
        $staff = TblStaff::whereHas('user', function($query) {
            $query->where('deleted', '0');
        })->count();
        $courses = TblCourse::where('deleted', '0')->count();

        // Fetch monthly registration stats for the current year
        $monthlyRegistrations = TblStudent::selectRaw('MONTH(createdate) as month, COUNT(*) as count')
            ->whereYear('createdate', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Prepare chart data (12 months)
        $registrationData = [];
        for ($i = 1; $i <= 12; $i++) {
            $registrationData[] = $monthlyRegistrations[$i] ?? 0;
        }

        $cards = [
            [
                'title' => 'Total Students',
                'value' => $students,
                'icon' => 'fa-user-graduate',
                'bg_color' => 'bg-blue-50',
                'text_color' => 'text-blue-600',
            ],
            [
                'title' => 'Total Staff',
                'value' => $staff,
                'icon' => 'fa-chalkboard-user',
                'bg_color' => 'bg-green-50',
                'text_color' => 'text-green-600',
            ],
            [
                'title' => 'Total Courses',
                'value' => $courses,
                'icon' => 'fa-book',
                'bg_color' => 'bg-purple-50',
                'text_color' => 'text-purple-600',
            ],
        ];

        return compact('cards', 'registrationData');
    }
}
