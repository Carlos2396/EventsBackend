<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

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

    private static $rules = [
        'email' => 'required|string|email|max:255|unique:users',
        'firstname' => 'required|string|max:100',
        'lastname' => 'required|string|max:100',
        'birthdate' => 'required|date',
        'gender' => 'required|string|alpha',
        'phone' => 'required|numeric',
        'alias' => 'nullable|string|max:100'
    ];

    public static function validate($data) {
        $rules = array_merge(self::$rules, ['password' => 'required|string|min:6|confirmed']);

        return Validator::make($data, $rules);
    }

    public static function validateUpdate($data) {
        return Validator::make($data, self::$rules);
    }

    public static function validateChangePassword($data) {
        return Validator::make($data, [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }
}
