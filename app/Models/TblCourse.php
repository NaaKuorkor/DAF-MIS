<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblCourse extends Model
{
    protected $table = 'tblcourse';

    protected $fillable = [
        'courseid',
        'course_name',
        'duration',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function user() {
            return $this->
    }
}
