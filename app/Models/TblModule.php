<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblModule extends Model
{
    protected $table = 'tblmodule';

    protected $primaryKey = 'mod_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'modid',
        'mod_position',
        'mod_name',
        'mod_label',
        'mod_url',
        'is_child',
        'pmod',
        'has_child',
        'icon_class',
        'mod_status',
        'mod_icon',
    ];
}
