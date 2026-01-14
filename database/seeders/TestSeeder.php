<?php

namespace Database\Seeders;

use App\Models\TblCohort;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TblStaff;
use App\Models\TblCourse;
use App\Models\TblUser;
use Illuminate\Support\Facades\Hash;


class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = TblUser::create([
            'userid' => 'U0000000001',
            'email' => 'naakuorkor72@gmail.com',
            'phone' => '0595319756',
            'password' => Hash::make('Decode@20'),
            'user_type' => 'ADM',
            'deleted' => '0',
        ]);

        $staff->staff()->create([
            'staffid' => 'STF0000000001',
            'fname' => 'Dorothy',
            'lname' => 'Amon',
            'gender' => 'F',
            'age' => 30,
            'residence' => 'Dansoman',
            'position' => 'Trainer',
            'department' => 'Training',
            'createuser' => 'system',
            'modifyuser' => 'system'
        ]);

        $student = TblUser::create([
            'userid' => 'U0000000002',
            'email' => 'naakotey52@gmail.com',
            'phone' => '0242167206',
            'password' => Hash::make('Decode@20'),
            'user_type' => 'STU',
            'deleted' => '0',

        ]);

        $student->student()->create([
            'studentid' => 'STU0000000001',
            'fname' => 'Naa',
            'lname' => 'Kuorkor',
            'gender' => 'F',
            'age' => 20,
            'residence' => 'La',
            'employment_status' => 'Unemployed',
            'certificate' => 'Y',
            'referral' => 'Website',
            'createuser' => 'system',
            'modifyuser' => 'system'
        ]);

        TblCourse::create([
            'course_id' => 'LS101',
            'course_name' => 'Life Skills 101',
            'description' => 'Skills meant to equip the youth for the corporate world ahead',
            'duration' => '4 days',
            'createuser' => 'system',
            'modifyuser' => 'system',
        ]);

        TblCohort::create([
            'cohort_id' => 'CLS1',
            'course_id' => 'LS101',
            'description' => 'First cohort in November',
            'start_date' => '2025-11-5',
            'end_date' => '2025-11-10',
            'student_limit' => 20,
            'createuser' => 'system',
            'modifyuser' => 'system',

        ]);


        $studentInstance = $student->student()->first();

        $studentInstance->course_registration()->create([
            'course_id' => 'LS101',
            'studentid' =>  $studentInstance->studentid,
            'createuser' => 'system',
            'modifyuser' => 'system',
        ]);

        $studentInstance->cohort_registration()->create([
            'cohort_id' => 'CLS1',
            'studentid' => $studentInstance->studentid,
            'createuser' => 'system',
            'modifyuser' => 'system',

        ]);
    }
}
