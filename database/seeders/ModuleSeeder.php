<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\TblModule;
use App\Models\TblUserModulePriviledges;

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
                'mod_name' => 'Overview',
                'mod_label' => 'Overview',
                'mod_url' => '/overview',
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
                'mod_url' => '/staff/student-info',
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
                'mod_url' => '/courses',
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

            ],
            [
                'modid' => 'MOD007',
                'mod_position' => '7',
                'mod_name' => 'Account Management',
                'mod_label' => 'My Account',
                'mod_url' => '/my-account',
                'is_child' => 0,
                'pmod_id' => null,
                'has_child' => 0,
                'icon_class' => 'fa-solid file',
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

        $modulePriviledges = [
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD001',
                'mod_create' => 0,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'
            ],
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD002',
                'mod_create' => 0,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'
            ],
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD003',
                'mod_create' => 1,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'

            ],
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD004',
                'mod_create' => 1,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'

            ],
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD005',
                'mod_create' => 1,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'
            ],
            [
                'userid' => 'U0000000001',
                'modid' => 'MOD006',
                'mod_create' => 1,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 1,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'
            ],
            [
                'userid' => 'U0000000002',
                'modid' => 'MOD001',
                'mod_create' => 0,
                'mod_read' => 1,
                'mod_update' => 0,
                'mod_delete' => 0,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'

            ],
            [
                'userid' => 'U0000000002',
                'modid' => 'MOD004',
                'mod_create' => 0,
                'mod_read' => 1,
                'mod_update' => 0,
                'mod_delete' => 0,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'
            ],
            [
                'userid' => 'U0000000002',
                'modid' => 'MOD007',
                'mod_create' => 0,
                'mod_read' => 1,
                'mod_update' => 1,
                'mod_delete' => 0,
                'createdate' => Carbon::now(),
                'createuser' => 'system',
                'modifydate' => Carbon::now(),
                'modifyuser' => 'system'

            ]
        ];

        TblUserModulePriviledges::insert($modulePriviledges);
    }
}
