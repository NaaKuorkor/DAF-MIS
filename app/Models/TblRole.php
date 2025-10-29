<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;

class TblRole extends Model
{
    protected $table = 'tblrole';

    protected $fillable = [
        'role_name',
        'role_description',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function user() {
        return $this->hasMany(TblUser::class, 'role', 'role_id');
    }
}
