<?php

namespace App\Http\Controllers;

use App\Models\TblStaff;
use Illuminate\Http\Request;

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
}
