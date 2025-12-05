<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;
use App\Models\TblCourseRegistration;
use App\Models\TblCohortRegistration;


class TblStudent extends Model
{
    protected $table = 'tblstudent';

    protected $primaryKey = 'studentid';

    public $incrementing = false;

    public $keytype = 'string';

    public $timestamps = false;

    protected $fillable = [
        'userid',
        'studentid',
        'fname',
        'mname',
        'lname',
        'gender',
        'age',
        'residence',
        'referral',
        'employment_status',
        'qualification',
        'certificate',
        'payment',
        'job_preference',
    ];

    protected function casts(): array
    {
        return [];
    }



    public function user()
    {
        return $this->belongsTo(TblUser::class, 'userid', 'userid')
            ->where('deleted', 0);
    }


    public function course_registration()
    {
        return $this->hasMany(TblCourseRegistration::class, 'studentid', 'studentid');
    }

    public function cohort_registration()
    {
        return $this->hasMany(TblCohortRegistration::class, 'studentid', 'studentid');
    }
}
