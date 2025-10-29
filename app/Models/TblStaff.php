<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TblStaff extends Authenticatable
{
    protected $table = 'tblstaff';

    protected $fillable = [
        'user_id',
        'staff_id',
        'fname',
        'mname',
        'lname',
        'gender',
        'role',
        'position',
    ];

    protected function casts(): array
    {
        return [];
    }

    protected static function boot() {
        parent::boot();

        static::creating(function($staff) {
            if (empty($staff->staff_id)){
                $staff->staff_id = self::generateId();
            }
        });
    }

    private static function generateId() {

        $lastStaff = self::orderBy('staff_id', 'desc')->first();

        if (!$lastStaff) {
            return 'STF001';
        }

        $lastNumber = (int) substr($lastStaff->staff_id, 3);
        $newNumber = ++$lastNumber;

        return 'STF' .str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function user(){
        return $this->belongsTo(TblUser::class, 'staffid', 'userid');
    }

}
