<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function organizedEvents(){
        return $this->hasMany('App\Models\Event', 'organizer_id' );
    }

    public function extras(){
        return $this->belongsToMany('App\Models\Extra', 'answers')->withPivot('answer');
    }

    public function events(){
        return $this->belongsToMany('App\Models\Event', 'tickets')->withPivot('code')->withTimestamps();
    }
}
