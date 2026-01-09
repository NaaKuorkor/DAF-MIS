<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAnnouncementRead extends Model
{
    protected $table = 'tblannouncement_reads';
    public $timestamps = false;

    protected $fillable = [
        'announcement_id',
        'userid',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function announcement()
    {
        return $this->belongsTo(TblAnnouncements::class, 'announcement_id', 'announcement_id');
    }

    public function user()
    {
        return $this->belongsTo(TblUser::class, 'userid', 'userid');
    }
}