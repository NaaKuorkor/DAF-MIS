<?php

namespace App\Http\Controllers;

use App\Mail\Verify;
use App\Models\TblCourseRegistration;
use App\Models\TblStudent;
use App\Models\TblUser;
use App\Models\TblUserModulePriviledges;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class StudentMngtController extends Controller
{
    public function studentTableContent()
    {
        try {
            $students = TblStudent::with(
                'cohort_registration.cohort',
                'course_registration.course',
                'user'
            )->whereHas('user', function ($query) {
                $query->where('deleted', '0');
            })
                ->orderBy('createdate', 'desc')
                ->paginate(10);

            $students->getCollection()->transform(function ($student) {
                $courseRegistration = $student->course_registration->first();
                $cohortRegistration = $student->cohort_registration->first();
                
                return [
                    'name' => trim($student->lname . ' ' . ($student->mname ?? '') . ' ' . $student->fname),
                    'course' => $courseRegistration?->course->course_name ?? 'N/A',
                    'cohort' => $cohortRegistration?->cohort->cohort_id ?? 'N/A',
                    'registration_date' => $courseRegistration?->createdate ?? null,
                    'studentid' => $student->studentid,
                    'userid' => $student->user?->userid ?? null,
                    'fname' => $student->fname,
                    'mname' => $student->mname ?? '',
                    'lname' => $student->lname,
                    'age' => $student->age,
                    'email' => $student->user?->email ?? '',
                    'phone' => $student->user?->phone ?? '',
                    'referral' => $student->referral ?? '',
                    'residence' => $student->residence ?? '',
                    'employment_status' => $student->employment_status ?? '',
                    'certificate' => $student->certificate ?? '',
                    'course_id' => $courseRegistration?->course_id ?? null,
                    'gender' => $student->gender ?? '',
                ];
            });

            return response()->json($students);
        } catch (\Exception $e) {
            Log::error('Student table content failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load students: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function alphaStudentFilter()
    {

        $students = TblStudent::with(
            'cohort_registration.cohort',
            'course_registration.course',
            'user'
        )            ->whereHas('user', function ($query) {
                $query->where('deleted', '0');
            })
            ->orderBy('lname', 'asc')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            $courseRegistration = $student->course_registration->first();
            $cohortRegistration = $student->cohort_registration->first();
            
            return [
                'name' => trim($student->lname . ' ' . ($student->mname ?? '') . ' ' . $student->fname),
                'course' => $courseRegistration?->course->course_name ?? 'N/A',
                'course_id' => $courseRegistration?->course_id ?? 'LS101',
                'cohort' => $cohortRegistration?->cohort->cohort_id ?? 'N/A',
                'registration_date' => $courseRegistration?->createdate ?? null,
                'studentid' => $student->studentid,
                'userid' => $student->user?->userid ?? null,
                'fname' => $student->fname,
                'mname' => $student->mname ?? '',
                'lname' => $student->lname,
                'gender' => $student->gender ?? '',
                'age' => $student->age,
                'email' => $student->user?->email ?? '',
                'phone' => $student->user?->phone ?? '',
                'referral' => $student->referral ?? '',
                'residence' => $student->residence ?? '',
                'employment_status' => $student->employment_status ?? '',
                'certificate' => $student->certificate ?? '',
            ];
        });

        return response()->json($students);
    }

    public function searchStudent(Request $request)
    {
        $query = $request->input('q');

        if (!empty($query)) {
            $searchTerm = '%' . addcslashes($query, '%_') . '%';

            //Eagerload relationships and identify only undeleted students
            $students = TblStudent::with(
                'course_registration.course',
                'cohort_registration.cohort',
                'user'
            )            ->whereHas('user', function ($query) {
                $query->where('deleted', '0');
            })->where(
                function ($querybuilder) use ($searchTerm) {
                    $querybuilder->where('fname', 'LIKE', $searchTerm)
                        ->orWhere('lname', 'LIKE', $searchTerm)
                        ->orWhere('mname', 'LIKE', $searchTerm);
                }

            )->orderBy('createdate', 'desc')->paginate(10);

        $students->getCollection()->transform(function ($student) {
            $courseRegistration = $student->course_registration->first();
            $cohortRegistration = $student->cohort_registration->first();
            
            return [
                'name' => trim($student->lname . ' ' . ($student->mname ?? '') . ' ' . $student->fname),
                'course' => $courseRegistration?->course->course_name ?? 'N/A',
                'course_id' => $courseRegistration?->course_id ?? 'LS101',
                'cohort' => $cohortRegistration?->cohort->cohort_id ?? 'N/A',
                'registration_date' => $courseRegistration?->createdate ?? null,
                'studentid' => $student->studentid,
                'userid' => $student->user?->userid ?? null,
                'fname' => $student->fname,
                'mname' => $student->mname ?? '',
                'lname' => $student->lname,
                'gender' => $student->gender ?? '',
                'age' => $student->age,
                'email' => $student->user?->email ?? '',
                'phone' => $student->user?->phone ?? '',
                'referral' => $student->referral ?? '',
                'residence' => $student->residence ?? '',
                'employment_status' => $student->employment_status ?? '',
                'certificate' => $student->certificate ?? '',
            ];
        });

            return response()->json($students);
        } else {
            return $this->studentTableContent();
        }
    }


    public function updateStudent(Request $request)
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
            ];

            $user->update($userUpdate);
            $student->update($studentUpdate);

            // Update course registration if course changed
            if (isset($updates['course'])) {
                $courseRegistration = TblCourseRegistration::where('studentid', $updates['studentid'])->first();
                if ($courseRegistration) {
                    $courseRegistration->update([
                        'course_id' => $updates['course'],
                        'modifyuser' => auth()->user()->email ?? 'system',
                        'modifydate' => now(),
                    ]);
                }
            }

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

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed'
            ]);
        }
    }

    public function importStudents(Request $request, SmsService $sms)
    {
        //Check the kind of file first
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        //Load excel file
        $rows = SimpleExcelReader::create($request->file('file'))->getRows();

        $createdusers = collect();

        //Loop through each row creating user and student records

        $rows->each(function (array $row) use (&$createdusers) {
            //Wrap in transaction and pass row and createdusers by reference
            DB::transaction(function () use ($row, &$createdusers) {
                //Create user id
                $lastUser = DB::table('tbluser')
                    ->selectRaw("MAX(CAST(SUBSTRING(userid, 2) AS UNSIGNED)) as maxid")
                    ->lockForUpdate()
                    ->value('maxid');

                $newNum = ($lastUser ?? 0) + 1;
                $userid = "U" . str_pad($newNum, 10, "0", STR_PAD_LEFT);

                //Create student id
                $lastStudent = DB::table('tblstudent')
                    ->selectRaw("MAX(CAST(SUBSTRING(studentid, 2) AS UNSIGNED)) as maxid")
                    ->lockForUpdate()
                    ->value('maxid');

                $newNum = ($lastStudent ?? 0) + 1;
                $studentid = "STU" . str_pad($newNum, 10, "0", STR_PAD_LEFT);

                //Insert into tables and create other info
                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $row['Email'],
                    'phone' => $row['Phone'],
                    'password' => Hash::make($row['Phone']),
                    'user_type' => 'STU',
                    'deleted' => 0,
                    'createuser' => 'system',
                    'modifyuser' => 'system',
                ]);




                $student = TblStudent::create([
                    'userid' => $user->userid,
                    'studentid' => $studentid,
                    'fname' => $row['First Name'],
                    'mname' => $row['Middle Name'] ?? null,
                    'lname' => $row['Surname'],
                    'gender' => $row['Gender'],
                    'age' => $row['Age'],
                    'residence' => $row['Residence'],
                    'referral' => $row['Referral Source'],
                    'employment_status' => $row['Employment Status'],
                    'certificate' => $row['Certificate'],
                ]);

                TblCourseRegistration::create([
                    'studentid' => $student->studentid,
                    'course_id' => $row['Course'],
                    'createuser' => 'system',
                    'modifyuser' => 'system',
                ]);


                $modulePriviledges = [
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD001',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD004',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD007',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,

                    ]
                ];

                foreach ($modulePriviledges as $p) {
                    TblUserModulePriviledges::create([
                        'userid' => $p['userid'],
                        'modid' => $p['modid'],
                        'mod_create' => $p['mod_create'],
                        'mod_read' => $p['mod_read'],
                        'mod_update' => $p['mod_update'],
                        'mod_delete' => $p['mod_delete'],
                        'createuser' => 'system',
                        'modifyuser' => 'system',
                        'createdate' => now(),
                        'modifydate' => now()
                    ]);
                }

                $createdusers->push([
                    'user' => $user,
                    'student' => $student,
                ]);
            });
        });

        foreach ($createdusers as $entry) {
            $user = $entry['user'];
            $student = $entry['student'];

            //create verification link for user
            $verificationLink = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->userid, 'hash' => sha1($user->email)]
            );

            //Send mail with link
            Mail::to($user->email)->send(new Verify($student, $verificationLink));

            //Change phone format to have countrycode
            $phone = preg_replace('/^0/', '233', $user->phone);

            //Interpolate name, email and password within message
            $message = "Thank you {$student->fname} for registering to be a part of DAF.\n
                Your login credentials are as follows.\n
                Email : {$user->email}\n
                Password : {$user->phone}\n You have the liberty to change your password once you login.\n
                Enjoy your time with us! ";

            $sms->send($phone, $message);
        }
    }

    public function exportStudents()
    {
        //Get students with the user model instances
        $students = TblStudent::with('user')->get();

        //Create anin-excel file to be streamed to browser
        $writer = SimpleExcelWriter::streamDownload('students.xlsx');

        //Add data rows
        foreach ($students as $student) {
            $writer->addRow([
                'Name' => $student->lname . ' ' . $student->mname . ' ' . $student->fname,
                'Email' => $student->user->email,
                'Phone' => $student->user->phone,
                'Age'   => $student->age,
                'Gender' => $student->gender,
                'Residence' => $student->residence,
                'Referral Source' => $student->referral,
                'Employment Status' => $student->employment_status,
                'Certificate' => $student->certificate
            ]);
        }

        return $writer->toBrowser();
    }
}
