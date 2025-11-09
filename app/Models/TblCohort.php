<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblCohortRegistration;
use App\Models\TblStudent;
use App\Models\TblCourse;

class TblCohort extends Model
{
    protected $table = "tblcohort";

    protected $primaryKey = 'transid';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'cohort_id',
        'course_id',
        'start_date',
        'end_date',
    ];

    protected function cast(): array
    {
        return [];
    }

    public function cohortRegistration()
    {
        return $this->hasMany(TblCohortRegistration::class, 'cohort_id', 'cohort_id');
    }

    //Each cohort has one course
    public function course()
    {
        return $this->belongsTo(TblCourse::class, 'course_id', 'course_id');
    }

    //Each cohort has multiple students via the cohort registration table
    public function students()
    {
        return $this->belongsToMany(TblStudent::class, 'tblcohort_registration', 'cohort_id', 'studentid');
    }
}
