<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblTask extends Model
{
    protected $table = 'tbltask';
    protected $primaryKey = 'task_id';

    public $incrementing = false;
    public $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'userid',
        'title',
        'description',
        'due_date',
        'priority',
        'status'
    ];

    public function user()
    {

        return $this->belongsTo(TblUser::class, 'userid', 'userid');
    }
}
