<?php

namespace App\Http\Controllers;

use App\Models\TblCohort;
use App\Models\TblCohortRegistration;
use App\Models\TblCourse;
use App\Models\TblCourseRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CohortController extends Controller
{
    public function createCohort(Request $request)
    {
        try {

            $fields = $request->validate([
                'course_id' => 'required|string|exists:tblcourse,course_id',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'student_limit' => 'nullable|integer|min:1',
            ]);

            //Think of structure for cohortid
            //Use random one
            $cohort_id = 'COH-' . rand(100, 999);

            TblCohort::create([
                'cohort_id' => $cohort_id,
                'course_id' => $fields['course_id'],
                'description' => $fields['description'],
                'start_date' => $fields['start_date'],
                'end_date' => $fields['end_date'],
                'student_limit' => $fields['student_limit'] ?? null,
                'is_completed' => 0,
                'deleted' => '0',
                'createuser' => auth()->user()->email,
                'modifyuser' => auth()->user()->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cohort created successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Cohort creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => "Cohort creation failed"
            ]);
        }
    }

    public function assignToCohort(Request $request)
    {

        $fields = $request->validate([
            'cohort_id' => 'required|string',
            'studentid' => 'required|string'
        ]);

        //Check for cohort in table
        $cohort = TblCohort::where('cohort_id', $fields['cohort_id'])
            ->where('deleted', '0')
            ->firstOrFail();

        $registered = TblCourseRegistration::where('studentid', $fields['studentid'])
            ->where('course_id', $cohort->course_id)
            ->exists();

        if (!$registered) {
            return response()->json([
                'success' => false,
                'message' => 'Student not registered for this course'
            ]);
        }

        $exists = TblCohortRegistration::where('studentid', $fields['studentid'])
            ->where('cohort_id', $fields['cohort_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Student already assigned to this cohort'
            ]);
        }

        TblCohortRegistration::create([
            'studentid' => $fields['studentid'],
            'cohort_id' => $fields['cohort_id'],
            'createuser' => auth()->user()->email,
            'modifyuser' => auth()->user()->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student assigned to cohort successfully'
        ]);
    }

    public function viewCohortsForCourse($course_id)
    {
        //Check if course exists
        $course = TblCourse::where('course_id', $course_id)
            ->where('deleted', '0')
            ->firstOrFail();

        $cohorts = TblCohort::where('course_id', $course_id)
            ->where('deleted', '0')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($cohort) {
                $studentCount = TblCohortRegistration::where(
                    'cohort_id',
                    $cohort->cohort_id
                )->count();

                $now = Carbon::now();
                $startDate = Carbon::parse($cohort->start_date);
                $endDate = Carbon::parse($cohort->end_date);

                if ($now->lt($startDate)) {
                    $status = 'open';
                } elseif ($now->between($startDate, $endDate)) {
                    $status = 'ongoing';
                } else {
                    $status = 'completed';
                }

                return [
                    'cohort_id'     => $cohort->cohort_id,
                    'description'   => $cohort->description ?? '',  // Handle null description
                    'start_date'    => $cohort->start_date,
                    'end_date'      => $cohort->end_date,
                    'status'        => $status,
                    'student_count' => $studentCount,  // Changed from student_count to match JS
                ];
            });

        return response()->json([
            'course' => [
                'course_id'   => $course->course_id,
                'course_name' => $course->course_name,
                'description' => $course->description,
            ],
            'cohorts' => $cohorts
        ]);
    }

    public function viewStudentsForCohort($cohort_id)
    {
        $cohort = TblCohort::where('cohort_id', $cohort_id)
            ->where('deleted', '0')
            ->firstOrFail();

        $students = TblCohortRegistration::where(
            'tblcohort_registration.cohort_id',
            $cohort_id
        )
            ->join('tblstudent', 'tblstudent.studentid', '=', 'tblcohort_registration.studentid')
            ->join('tblcohort', 'tblcohort.cohort_id', '=', 'tblcohort_registration.cohort_id')
            ->join('tblcourse', 'tblcourse.course_id', '=', 'tblcohort.course_id')
            ->select([
                'tblstudent.studentid',
                DB::raw("CONCAT(tblstudent.fname, ' ', COALESCE(tblstudent.mname, ''), ' ', tblstudent.lname) AS student_name"),
                'tblstudent.referral',
                'tblcourse.course_name',
                'tblcohort_registration.createdate as registered_on',
            ])
            ->orderBy('tblcohort_registration.createdate', 'desc')
            ->paginate(15);

        return response()->json([
            'cohort' => [
                'cohort_id'   => $cohort->cohort_id,
                'description' => $cohort->description,
                'course_id'   => $cohort->course_id,
            ],
            'students' => $students
        ]);
    }

    public function deleteCohort($cohort_id)
    {
        try {
            $cohort = TblCohort::where('cohort_id', $cohort_id)
                ->where('deleted', '0')
                ->firstOrFail();

            // Soft delete - set deleted flag instead of actually deleting
            $cohort->update([
                'deleted' => '1',
                'modifyuser' => auth()->user()->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cohort deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Cohort deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cohort'
            ], 500);
        }
    }
}
