<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TblStudent extends Authenticatable
{
    protected $table = 'tblstudent';

    protected $fillable = [
        'user_id',
        'student_id',
        'fname',
        'mname',
        'lname',
        'gender',
    ];

    protected function casts(): array
    {
         return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            //If student_id is empty, execute generateId function
            //Incase there is the need for an overide
            if (empty($student->student_id)) {
                $student->student_id = self::generateId();
            }
        });
    }

    private static function generateId(){
        //Get student with the highest id
        $lastStudent = self::orderBy('student_id', 'desc')->first();

        if (!$lastStudent) {
            return 'S001';
        }

        $lastNumber = (int) substr($lastStudent->student_id, 1);
        $newNumber = ++$lastNumber;

        //Make increment and add to the string again
        return 'S' .str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function user() {
        return $this->belongsTo(TblUser::class, 'user_id', 'user_id');
    }
}
