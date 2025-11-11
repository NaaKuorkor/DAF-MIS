<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\TblStaff;
use App\Models\TblStudent;
use App\Models\TblModule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class TblUser extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'tbluser';

    protected $primaryKey = 'userid';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'userid',
        'email',
        'password',
        'phone',
        'user_type',
        'deleted',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     *
     * */

    protected function casts(): array
    {
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->createuser)) {
                $user->createuser = Auth::check() ? Auth::user()->email : 'system';
            }
        });
    }


    public function staff()
    {
        return $this->hasOne(TblStaff::class, 'userid', 'userid');
    }

    public function student()
    {
        return $this->hasOne(TblStudent::class, 'userid', 'userid');
    }

    public function role()
    {
        return $this->hasOne(TblRole::class, 'role_id', 'user_type');
    }


    //A user has access to many modules and a module has many users
    public function modules()
    {
        return $this->belongsToMany(TblModule::class, 'tbluser_module_priviledges', 'userid', 'modid')
            ->withPivot('mod_create', 'mod_read', 'mod_update', 'mod_delete') //Adds these columns as priviledge flags of the user
            ->where('tblmodule.mod_status', '1') //This only returns active modules
            ->orderBy('tblmodule.mod_position', 'asc');   // THis arranges them in ascending order
    }
}
