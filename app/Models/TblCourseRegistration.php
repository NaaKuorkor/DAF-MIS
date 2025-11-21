<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblCourse;

class TblCourseRegistration extends Model
{
    protected $table = 'tblcourse_registration';

    protected $primaryKey = ['studentid', 'course_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'studentid',
        'course_id',
        'is_completed'
    ];

    public function course()
    {
        return $this->belongsTo(TblCourse::class, 'course_id', 'course_id');
    }
}
