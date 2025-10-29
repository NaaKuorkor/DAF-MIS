<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Facades\Auth;
use App\Models\TblStaff;
use App\Models\TblStudent;
use App\Models\TblRole;
#use Illuminate\Contracts\Auth\MustVerifyEmail;

class TblUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'tbluser';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
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
    * */init

    protected function casts(): array
    {
        return [];
    }

    protected static function boot (){
        parent::boot();

        static::creating(function($user) {
            if(empty($user->createuser){
                $user->createuser = Auth::check() ? Auth::user()->email : 'system';
            })
        });
    }


    public function staff() {
        return $this->hasOne(TblStaff::class, 'user_id', 'user_id');
    }

    public function student() {
        return $this->hasOne(TblStudent::class, 'user_id, user_id');
    }


}
