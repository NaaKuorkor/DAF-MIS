<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TblCohort;
use App\Models\TblCohortRegistration;
use App\Models\TblCourse;
use App\Models\TblCourseRegistration;
use Illuminate\Support\Facades\Log;

class CourseCohortController extends Controller
{

    public function index()
    {
        try {
            $student = auth()->user()->student;

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found'
                ], 404);
            }

            // Try to get course registration - don't fail if not found
            // Note: tblcourse_registration doesn't have a deleted column
            $registration = TblCourseRegistration::where('studentid', $student->studentid)
                ->first();

            $course = null;
            if ($registration) {
                $course = TblCourse::where('course_id', $registration->course_id)
                    ->where('deleted', '0')
                    ->first();
            }

            // Check cohort registration - this is the key relationship
            // Note: tblcohort_registration doesn't have a deleted column
            $cohortRegistration = TblCohortRegistration::where('studentid', $student->studentid)
                ->first();

            $cohort = null;
            if ($cohortRegistration) {
                $cohort = TblCohort::where('cohort_id', $cohortRegistration->cohort_id)
                    ->where('deleted', '0')
                    ->first();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'cohort' => $cohort
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Courses failed to load', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course failed to load: ' . $e->getMessage()
            ], 500);
        }
    }
}
