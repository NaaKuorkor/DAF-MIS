<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStaff;
use App\Models\TblStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{


    public function createStaff(Request $request)
    {

        try {

            //Validate data
            $staffData = $request->validate([
                'fname' => 'string|required|max:50',
                'mname' => 'nullable|string|max:50',
                'lname' => 'string|required|max:50',
                'email' => 'email|required',
                'gender' => 'string|required|max:1',
                'age' => 'number|required',
                'position' => 'string|required|max:100',
                'phone' => 'string|required|max:15',
                'password' => 'required|string|min:6',
                'user_type' => 'required|string',
            ]);



            DB::transaction(function () use ($staffData) {


                $idCount =  DB::table('tbluser')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');
                $userid = null;

                if ($idCount === 0) {
                    $userid = 'U0000000001';
                } else {
                    $newNum = $idCount + 1;

                    $userid = 'U' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }


                $staffCount =   DB::table('tblstaff')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $staffid = null;

                if ($staffCount === 0) {
                    $staffid = 'STF0000000001';
                } else {
                    $newNum = $staffCount + 1;
                    $staffid = 'STF' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }


                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $staffData['email'],
                    'password' => Hash::make($staffData['password']),
                    'phone' => $staffData['phone'],
                    'usertype' => $staffData['user_type'],
                    'createdate' => now(),
                    'createuser' => auth()->user()->email ?? 'system',
                    'modifydate' => now(),
                    'modifyuser' => auth()->user()->email ?? 'system',

                ]);

                $staff = TblStaff::create([
                    'userid' => $user->userid,
                    'staffid' => $staffid,
                    'fname' => $staffData['fname'],
                    'mname' => $staffData['mname'] ?? null,
                    'lname' => $staffData['lname'],
                    'gender' => $staffData['gender'],
                    'age' => $staffData['age'],
                    'position' => $staffData['position'],

                ]);

                DB::statement('UNLOCK TABLES');
            });

            return back()->with('success', "Staff created successfully!");
        } catch (\Exception $e) {
            Log::error('Staff registration failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            DB::statement('UNLOCK TABLES');

            return back()->withErrors('Registration failed');
        }
    }


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

    public function staffTableContent()
    {
        $staff = TblStaff::with(
            'user'
        )->orderBy('createdate', 'desc')
            ->paginate(10);


        $staff->getCollection()->transform(function ($s) {
            return [
                'name' => $s->lname . ' ' . $s->mname . ' ' . $s->fname,
                'department' => $s->department,
                'position' => $s->position,
                'fname' => $s->fname,
                'mname' => $s->mname,
                'lname' => $s->lname,
                'gender' => $s->gender,
                'age' => $s->age,
                'staffid' => $s->staffid,
                'userid' => $s->user->userid,
            ];
        });

        return response()->json($staff);
    }

    public function alphaStaffFilter()
    {
        $staff = TblStaff::with(
            'user'
        )->orderBy('lname', 'desc')
            ->paginate(10);

        $staff->getCollection()->transform(function ($s) {
            return [
                'name' => $s->lname . ' ' . $s->mname . ' ' . $s->fname,
                'department' => $s->department,
                'position' => $s->position,
                'fname' => $s->fname,
                'mname' => $s->mname,
                'lname' => $s->lname,
                'gender' => $s->gender,
                'age' => $s->age,
                'staffid' => $s->staffid,
                'userid' => $s->user->userid,
            ];
        });

        return response()->json($staff);
    }

    public function editStudent() {}
}
