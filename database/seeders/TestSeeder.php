<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TblStaff;
use App\Models\TblStudent;
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
            'deleted' => 0,
        ]);

        $staff->staff()->create([
            'staffid' => 'STF0000000001',
            'fname' => 'Dorothy',
            'lname' => 'Amon',
            'gender' => 'F',
            'residence' => 'Dansoman',
            'position' => 'Developer',

        ]);

        $student = TblUser::create([
            'userid' => 'U0000000002',
            'email' => 'naakuorkor2@gmail.com',
            'phone' => '0242167206',
            'password' => Hash::make('Decode@20'),
            'user_type' => 'STU',
            'deleted' => 0,

        ]);

        $student->student()->create([
            'studentid' => 'STU0000000001',
            'fname' => 'Naa',
            'lname' => 'Kuorkor',
            'gender' => 'F',
            'residence' => 'La',
            'employment_status' => 'Unemployed',
            'certificate' => 'Y',
            'referral' => 'Website'
        ]);
    }
}
