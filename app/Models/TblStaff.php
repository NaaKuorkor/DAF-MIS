<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;


class TblStaff extends Model
{
    protected $table = 'tblstaff';

    protected $primaryKey = 'staffid';

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'userid',
        'staffid',
        'fname',
        'mname',
        'lname',
        'gender',
        'age',
        'residence',
        'position',
        'department'
    ];

    protected function casts(): array
    {
        return [];
    }

    public function user()
    {
        return $this->belongsTo(TblUser::class, 'userid', 'userid')
            ->where('deleted', '0');
    }
}
