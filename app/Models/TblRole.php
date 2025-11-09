<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;

class TblRole extends Model
{
    protected $table = 'tblrole';

    protected $fillable = [
        'role_id',
        'role_description',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function user()
    {
        return $this->hasMany(TblUser::class, 'user_type', 'role_id');
    }
}
