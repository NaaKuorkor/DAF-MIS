<?php

namespace App\Http\Controllers;

use App\Models\TblStudent;
use App\Models\TblUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentMngtController extends Controller
{
    public function studentTableContent()
    {

        $students = TblStudent::with(
            'cohort_registration.cohort',
            'course_registration.course',
            'user'
        )->whereHas('user', function ($query) {
            $query->where('deleted', 0);
        })
            ->orderBy('createdate', 'desc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            return [
                'name' => $student->lname . ' ' . $student->mname . ' ' . $student->fname,
                'course' => $student->course_registration[0]->course->course_name ?? 'N/A',
                'cohort' => $student->cohort_registration[0]->cohort->cohort_id ?? 'N/A',
                'registration_date' => $student->course_registration[0]->createdate,
                'studentid' => $student->studentid,
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
        )->whereHas('user', function ($query) {
            $query->where('deleted', 0);
        })
            ->orderBy('lname', 'asc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            return [
                'name' => $student->lname . ' ' . $student->mname . ' ' . $student->fname,
                'course' => $student->course_registration[0]->course->course_name ?? 'N/A',
                'cohort' => $student->cohort_registration[0]->cohort->cohort_id ?? 'N/A',
                'registration_date' => $student->course_registration[0]->createdate,
                'studentid' => $student->studentid,
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


    public function update(Request $request)
    {
        try {
            $updates = $request->validate([
                'userid' => 'required|string',
                'studentid' => 'required|string',
                'fname' => 'required|string|max:255',
                'mname' => 'nullable|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'gender' => 'required|string',
                'age' => 'required|integer',
                'residence' => 'required|string',
                'referral' => 'required|string',
                'employment_status' => 'required|string',
                'certificate' => 'required|string',
                'course' => 'required|string',
            ]);

            //Find user in student and user tables
            $user = TblUser::findOrFail($updates['userid']);

            $student = TblStudent::findOrFail($updates['studentid']);

            //Separate data for each table
            $userUpdate = [
                'userid' => $updates['userid'],
                'email' => $updates['email'],
                'phone' => $updates['phone'],
            ];
            $studentUpdate = [
                'userid' => $updates['userid'],
                'studentid' => $updates['studentid'],
                'fname' => $updates['fname'],
                'mname' => $updates['mname'],
                'lname' => $updates['lname'],
                'gender' => $updates['gender'],
                'age' => $updates['age'],
                'residence' => $updates['residence'],
                'referral' => $updates['referral'],
                'employment_status' => $updates['employment_status'],
                'certificate' => $updates['certificate'],
                'course' => $updates['course'],
            ];

            $user->update($userUpdate);
            $student->update($studentUpdate);

            return response()->json([
                'success' => true,
                'message' => 'Records updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error(
                'Updates failed',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Updates failed',
            ]);
        }
    }

    public function deleteStudent(Request $request)
    {
        try {
            $userid = $request->userid;

            //Update deleted to 1
            $deleted = TblUser::where('userid', $userid)->update(['deleted' => 1]);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deletion successfull!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Deletion failed. User not found!'
                ]);
            }
        } catch (\Exception $e) {
            Log::error(
                'Deletion failed',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }
}
