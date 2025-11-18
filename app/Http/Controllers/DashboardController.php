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

    public function getContent($route)
    {
        $view = view('module.$route')->render();

        return response($view);
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

        $html = view('components.overview', compact('cards'));

        return response($html);
    }
}
