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



    public function user()
    {
        return $this->belongsTo(TblUser::class, 'userid', 'userid');
    }
}
