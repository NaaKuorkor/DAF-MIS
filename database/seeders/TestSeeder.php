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
        $user = TblUser::create([
            'email' => 'johndoe@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123456789'),
            'user_type' => 'STU',
            'deleted' => 0,
        ]);

        $user->student()->create([
            'fname' => 'John',
            'lname' => 'Doe',
            'gender' => 'M',
            'residence' => 'Dansoman',
            'employment_status' => 'Unemployed',
            'certificate' => 'Pending',

        ]);

        $staffUser = TblUser::create([
            'email' => 'fredbigs@gmail.com',
            'password' => Hash::make('987654321'),
            'phone' => '0987654321',
            'user_type' => 'STA',
            'deleted' => 0,
        ]);

        $staffUser->staff()->create([
            'fname' => 'Fred',
            'lname' => 'Bigs',
            'gender' => 'M',
            'position' => 'Accountant',
            'residence' => 'Abeka',
        ]);
    }
}
