<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblUser;

class TblAnnouncements extends Model
{
    protected $table = 'tblannouncements';
    protected $primaryKey = 'announcement_id';
    public $incrementing = false;
    public $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'announcement_id',
        'title',
        'content',
        'status',
        'priority',
        'audience',
        'scheduled_at',
        'published_at',
        'expires_at',
        'views_count',
        'read_count',
        'created_by',
        'deleted',
        'createuser',
        'createdate',
        'modifyuser',
        'modifydate',
    ];

    protected $casts = [
        'audience' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'createdate' => 'datetime',
        'modifydate' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(TblUser::class, 'created_by', 'userid');
    }

    public function reads()
    {
        return $this->hasMany(TblAnnouncementRead::class, 'announcement_id', 'announcement_id');
    }

    public function isReadBy($userid)
    {
        return $this->reads()->where('userid', $userid)->exists();
    }

    public function markAsRead($userid)
    {
        if (!$this->isReadBy($userid)) {
            TblAnnouncementRead::create([
                'announcement_id' => $this->announcement_id,
                'userid' => $userid,
            ]);
            $this->increment('read_count');
        }
    }
}