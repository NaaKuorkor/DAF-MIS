<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblStudent;
use App\Models\TblCourseRegistration;
use App\Models\TblCohort;

class TblCourse extends Model
{
    protected $table = 'tblcourse';

    protected $primaryKey = 'course_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'course_name',
        'duration',
    ];

    protected function casts(): array
    {
        return [];
    }


    //Each course has many students via egistrations
    public function students()
    {
        return $this->belongsToMany(TblStudent::class, 'tblcourse_registration', 'course_id', 'studentid');
    }

    //Each course has many registrations
    public function courseRegistrations()
    {
        return $this->hasMany(TblCourseRegistration::class, 'course_id', 'course_id');
    }

    //Each course can have many cohorts
    public function cohort()
    {
        return $this->hasMany(TblCohort::class, 'course_id', 'course_id');
    }
}
