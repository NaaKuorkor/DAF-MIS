<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TblStaff extends Authenticatable
{
    protected $table = 'tblstaff';

    protected $primaryKey = 'staffid';

    public $timestamps = false;

    protected $fillable = [
        'userid',
        'staffid',
        'fname',
        'mname',
        'lname',
        'gender',
        'residence',
        'position',
    ];

    protected function casts(): array
    {
        return [];
    }
}
