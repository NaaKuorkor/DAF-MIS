<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblModule;

class TblUserModulePriviledges extends Model
{
    protected $table = 'tbluser_module_priviledges';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'userid',
        'modid',
        'mod_create',
        'mod_read',
        'mod_update',
        'mod_delete',
        'createuser',
        'modifyuser',
    ];
}
