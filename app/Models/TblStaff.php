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

    protected static function boot() {
        parent::boot();

        static::creating(function($staff) {
            if (empty($staff->staffid)){
                $staff->staffid = self::generateId();
            }
        });
    }

    private static function generateId() {

        $lastStaff = self::orderBy('staffid', 'desc')->first();

        if (!$lastStaff) {
            return 'STF001';
        }

        $lastNumber = (int) substr($lastStaff->staffid, 3);
        $newNumber = ++$lastNumber;

        return 'STF' .str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function user(){
        return $this->belongsTo(TblUser::class, 'userid', 'userid');
    }

}
