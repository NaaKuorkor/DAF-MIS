<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;

class TblModule extends Model
{
    protected $table = 'tblmodule';

    protected $primaryKey = 'modid';

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

    public function users()
    {
        return $this->belongsToMany(TblUser::class, 'tbluser_module_priviledges', 'modid', 'userid')
            ->withPivot('mod_create', 'mod_read', 'mod_update', 'mod_delete', 'mod_report');
    }
}
