<?php

namespace App\Http\Controllers;

use App\Models\TblCourseRegistration;
use Illuminate\Http\Request;

class CourseRegistrationController extends Controller
{
    public function viewRegistrationsByCourse($course_id)
    {
        $registrations = TblCourseRegistration::with([
                'student.user',  // Eager load student and user
                'student.cohort_registration.cohort'  // Eager load student's cohort registrations and their cohorts
            ])
            ->where('course_id', $course_id)
            ->orderBy('createdate', 'desc')
            ->paginate(12);

        // Format the data to include student name, email, and cohort
        $formattedData = $registrations->getCollection()->map(function ($registration) use ($course_id) {
            $student = $registration->student;
            $user = $student->user ?? null;
            
            // Build full name from fname, mname, lname
            $nameParts = array_filter([
                $student->fname ?? '',
                $student->mname ?? '',
                $student->lname ?? ''
            ]);
            $fullName = implode(' ', $nameParts) ?: 'Unknown Student';
            
            // Find the cohort for this student in this course
            $cohort = null;
            if ($student->cohort_registration) {
                // Filter cohort registrations to find one where the cohort belongs to this course
                $cohortRegistration = $student->cohort_registration->first(function ($cr) use ($course_id) {
                    return $cr->cohort && $cr->cohort->course_id === $course_id;
                });
                
                if ($cohortRegistration && $cohortRegistration->cohort) {
                    $cohort = $cohortRegistration->cohort->cohort_id;
                }
            }
            
            return [
                'id' => $registration->studentid,
                'course_id' => $registration->course_id,
                'student' => [
                    'name' => $fullName,
                    'email' => $user->email ?? null,
                    'fname' => $student->fname ?? null,
                    'mname' => $student->mname ?? null,
                    'lname' => $student->lname ?? null,
                ],
                'cohort' => $cohort,
                'createdate' => $registration->createdate,
                'is_completed' => $registration->is_completed,
            ];
        });

        // Replace the collection with formatted data
        $registrations->setCollection($formattedData);

        return response()->json($registrations);
    }
}