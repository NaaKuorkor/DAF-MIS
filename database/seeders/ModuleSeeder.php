<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\TblModule;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'modid' => 'MOD001',
                'mod_position' => '1',
                'mod_name' => 'Dashboard',
                'mod_label' => 'Dashboard',
                'mod_url' => '/staff/dashboard',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-grip',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),
            ],
            [
                'modid' => 'MOD002',
                'mod_position' => '2',
                'mod_name' => 'Student Management',
                'mod_label' => 'Students',
                'mod_url' => '/staff/students',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-graduation-cap',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),

            ],
            [
                'modid' => 'MOD003',
                'mod_position' => '3',
                'mod_name' => 'Staff Management',
                'mod_label' => 'Staff',
                'mod_url' => '/staff/staff-info',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-person',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),
            ],
            [
                'modid' => 'MOD004',
                'mod_position' => '4',
                'mod_name' => 'Course Management',
                'mod_label' => 'Courses',
                'mod_url' => '/staff/courses',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-book-open',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),
            ],
            [
                'modid' => 'MOD005',
                'mod_position' => '5',
                'mod_name' => 'Cohort Management',
                'mod_label' => 'Cohorts',
                'mod_url' => '/staff/cohorts',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-people-group',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),
            ],
            [
                'modid' => 'MOD006',
                'mod_position' => '6',
                'mod_name' => 'Task Management',
                'mod_label' => 'Tasks',
                'mod_url' => '/staff/tasks',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid fa-list-check',
                'mod_status' => '1',
                'mod_icon' => null,
                'createuser' => 'system',
                'createdate' => Carbon::now(),
                'modifyuser' => 'system',
                'modifydate' => Carbon::now(),

            ]

        ];

        //Insert all in database
        TblModule::insert($modules);
    }
}
