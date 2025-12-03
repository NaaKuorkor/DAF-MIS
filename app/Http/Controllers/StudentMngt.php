<?php

namespace App\Http\Controllers;

use App\Models\TblStudent;
use Illuminate\Http\Request;

class StudentMngt extends Controller
{
    public function studentTableContent()
    {

        $students = TblStudent::with(
            'cohort_registration.cohort',
            'course_registration.course',
            'user'
        )->orderBy('createdate', 'desc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            return [
                'name' => $student->lname . ' ' . $student->mname . ' ' . $student->fname,
                'course' => $student->course_registration[0]->course->course_name ?? 'N/A',
                'cohort' => $student->cohort_registration[0]->cohort->cohort_id ?? 'N/A',
                'registration_date' => $student->course_registration[0]->createdate,
                'studentid' => $student->student_id,
                'userid' => $student->user->userid,
                'fname' => $student->fname,
                'mname' => $student->mname,
                'lname' => $student->lname,
                'age' => $student->age,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'referral' => $student->referral,
                'residence' => $student->residence,
                'employment_status' => $student->employment_status,
                'certificate' => $student->certificate,
            ];
        });


        return response()->json($students);
    }

    public function alphaStudentFilter()
    {

        $students = TblStudent::with(
            'cohort_registration.cohort',
            'course_registration.course',
            'user'
        )->orderBy('lname', 'asc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            return [
                'name' => $student->lname . ' ' . $student->mname . ' ' . $student->fname,
                'course' => $student->course_registration[0]->course->course_name ?? 'N/A',
                'cohort' => $student->cohort_registration[0]->cohort->cohort_id ?? 'N/A',
                'registration_date' => $student->course_registration[0]->createdate,
                'studentid' => $student->student_id,
                'userid' => $student->user->userid,
                'fname' => $student->fname,
                'mname' => $student->mname,
                'lname' => $student->lname,
                'age' => $student->age,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'referral' => $student->referral,
                'residence' => $student->residence,
                'employment_status' => $student->employment_status,
                'certificate' => $student->certificate,
            ];
        });

        return response()->json($students);
    }


    public function update(Request $request) {}
}
