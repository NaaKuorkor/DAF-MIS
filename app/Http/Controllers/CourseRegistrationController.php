<?php

namespace App\Http\Controllers;

use App\Models\TblCourseRegistration;
use Illuminate\Http\Request;

class CourseRegistrationController extends Controller
{
    public function viewRegistrationsByCourse($course_id)
    {
        $registrations = TblCourseRegistration::with([
                'student'
            ])
            ->where('course_id', $course_id)
            ->orderBy('createdate', 'desc')
            ->paginate(12);

        return response()->json($registrations);
    }
}
