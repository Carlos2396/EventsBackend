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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'email';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'firstname', 'lastname', 'birthdate', 'gender', 'phone', 'alias', 'image'
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'birthdate', 'confirmed_at' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'confirmation_code'
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

    /**
     * Rules for common attributes
     */
    private static $rules = [
        'firstname' => 'required|string|max:100',
        'lastname' => 'required|string|max:100',
        'birthdate' => 'required|date',
        'gender' => 'required|string|alpha',
        'phone' => 'required|numeric',
        'alias' => 'nullable|string|max:100'
    ];

    /**
     * Validates data required for registering a user
     */
    public static function validate($data) {
        $rules = array_merge(self::$rules, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        return Validator::make($data, $rules);
    }

    /**
     * Validates data for updating a user
     */
    public static function validateUpdate($data, $email) {
        if($data['email'] !== $email) {
            $rules = array_merge(self::$rules, [
                'email' => 'required|string|email|max:255|unique:users',
            ]);
        }
        else {
            $rules = self::$rules;
        }
        
        return Validator::make($data, $rules);
    }

    /**
     * Validates data for changing the password of the user
     */
    public static function validateChangePassword($data) {
        return Validator::make($data, [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }
}
