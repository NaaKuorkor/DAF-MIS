<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TblStudent extends Authenticatable
{
    protected $table = 'tblstudent';

    protected $primaryKey = 'studentid';

    public $timestamps = false;

    protected $fillable = [
        'userid',
        'studentid',
        'fname',
        'mname',
        'lname',
        'gender',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            //If studentid is empty, execute generateId function
            //Incase there is the need for an overide
            if (empty($student->studentid)) {
                $student->studentid = self::generateId();
            }
        });
    }

    public static function generateId(){
        //Get student with the highest id
        $lastStudent = self::orderByRaw("CAST(SUBSTRING(studentid, 2) AS UNSIGNED) DESC")->first();

        if (!$lastStudent) {
            return 'S001';
        }

        $lastNumber = (int) substr($lastStudent->studentid, 1);
        $newNumber = ++$lastNumber;

        //Make increment and add to the string again
        return 'S' .str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function user() {
        return $this->belongsTo(TblUser::class, 'userid', 'userid');
    }
}
