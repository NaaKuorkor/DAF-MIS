<?php

namespace App\Http\Controllers;

use App\Models\TblStaff;
use App\Models\TblUser;
use App\Models\TblUserModulePriviledges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class StaffMngtController extends Controller
{
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

    public function updateStaff(Request $request)
    {
        try {
            $updates = $request->validate([
                'userid' => 'required|string',
                'staffid' => 'required|string',
                'fname' => 'required|string|max:255',
                'mname' => 'nullable|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'gender' => 'required|string',
                'age' => 'required|integer',
                'residence' => 'required|string',
                'position' => 'required|string',
                'department' => 'required|string',

            ]);

            //Find user in student and user tables
            $user = TblUser::findOrFail($updates['userid']);

            $staff = TblStaff::findOrFail($updates['staffid']);

            //Separate data for each table
            $userUpdate = [
                'userid' => $updates['userid'],
                'email' => $updates['email'],
                'phone' => $updates['phone'],
            ];
            $staffUpdate = [
                'userid' => $updates['userid'],
                'staffid' => $updates['staffid'],
                'fname' => $updates['fname'],
                'mname' => $updates['mname'],
                'lname' => $updates['lname'],
                'gender' => $updates['gender'],
                'age' => $updates['age'],
                'residence' => $updates['residence'],
                'position' => $updates['position'],
                'department' => $updates['department'],
            ];

            $user->update($userUpdate);
            $staff->update($staffUpdate);

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

    public function deleteStaff(Request $request)
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
            Log::error('Deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed'
            ]);
        }
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

    public function importStaff(Request $request)
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
                $lastStaff = DB::table('tblstaff')
                    ->selectRaw("MAX(CAST(SUBSTRING(staffid, 4) AS UNSIGNED)) as maxid")
                    ->lockForUpdate()
                    ->value('maxid');

                $newNum = ($lastStaff ?? 0) + 1;
                $staffid = "STA" . str_pad($newNum, 10, "0", STR_PAD_LEFT);

                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $row['email'],
                    'password' => Hash::make($row['phone']),
                    'phone' => $row['phone'],
                    'usertype' => $row['user_type'],
                    'createdate' => now(),
                    'createuser' =>  'system',
                    'modifydate' => now(),
                    'modifyuser' => 'system',

                ]);

                $staff = TblStaff::create([
                    'userid' => $user->userid,
                    'staffid' => $staffid,
                    'fname' => $row['fname'],
                    'mname' => $row['mname'] ?? null,
                    'lname' => $row['lname'],
                    'gender' => $row['gender'],
                    'age' => $row['age'],
                    'position' => $row['position'],
                    'departent' => $row['department']

                ]);

                $modulePriviledges = [
                    [
                        //Overview
                        'userid' => $user->userid,
                        'modid' => 'MOD001',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        //Courses
                        'userid' => $user->userid,
                        'modid' => 'MOD004',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,

                    ],
                    [
                        //Tasks
                        'userid' => $user->userid,
                        'modid' => 'MOD006',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,
                    ],
                    [
                        //Account
                        'userid' => $user->userid,
                        'modid' => 'MOD007',
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,

                    ]
                ];

                foreach ($modulePriviledges as $priviledges) {
                    TblUserModulePriviledges::create([
                        'userid' => $priviledges['userid'],
                        'modid' => $priviledges['modid'],
                        'mod_create' => $priviledges['mod_create'],
                        'mod_read' => $priviledges['mod_read'],
                        'mod_update' => $priviledges['mod_update'],
                        'mod_delete' => $priviledges['mod_delete'],
                    ]);
                }


                $createdusers->push([
                    'user' => $user,
                    'staff' => $staff,
                ]);
            });
        });

        foreach ($createdusers as $entry) {
            $user = $entry['user'];
            $staff = $entry['staff'];

            //create verification link for user
            $verificationLink = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->userid, 'hash' => sha1($user->email)]
            );

            //Send mail with link
            Mail::to($user->email)->send(new Verify($staff, $verificationLink));

            //Change phone format to have countrycode
            $phone = preg_replace('/^0/', '233', $user->phone);

            //Interpolate name, email and password within message
            $message = "Thank you {$staff->fname} for registering to be a part of DAF.\n
                Your login credentials are as follows.\n
                Email : {$user->email}\n
                Password : {$user->phone}\n You have the liberty to change your password once you login.\n
                Enjoy your time with us! ";

            $this->sendSMS($phone, $message);
        }
    }

    public function exportStaff()
    {
        //Get students with the user model instances
        $staff = TblStaff::with('user')->get();

        //Create anin-excel file to be streamed to browser
        $writer = SimpleExcelWriter::streamDownload('Staff.xlsx');

        //Add data rows
        foreach ($staff as $s) {
            $writer->addRow([
                'Name' => $s->lname . ' ' . $s->mname . ' ' . $s->fname,
                'Email' => $s->user->email,
                'Phone' => $s->user->phone,
                'Age'   => $s->age,
                'Gender' => $s->gender,
                'Residence' => $s->residence,
                'Position' => $s->position,
                'Department' => $s->department,
            ]);
        }

        return $writer->toBrowser();
    }
}
