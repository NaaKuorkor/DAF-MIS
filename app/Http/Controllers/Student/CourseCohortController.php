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

            $registration = TblCourseRegistration::where('studentid', $student->studentid)
                ->where('deleted', '0')
                ->firstOrFail();

            $id = $registration->course_id;

            $course = TblCourse::where('course_id', $id)
                ->where('deleted', '0')
                ->firstOrFail();

            // Check cohort registration - this is the key relationship
            $cohortRegistration = TblCohortRegistration::where('studentid', $student->studentid)
                ->where('deleted', '0')
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
                'message' => 'Course failed to load'
            ], 500);
        }
    }
}
