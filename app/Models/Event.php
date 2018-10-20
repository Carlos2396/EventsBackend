<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Event extends Model
{
    protected $fillable = ['name', 'starts', 'end', 'registration_start', 'registration_end', 'image', 'description', 'organizer_id', 'guest_capacity', 'event_type'];

    public function locations(){
        return $this->hasMany('App\Models\Location');
    }

    public function sponsors(){
        return $this->hasMany('App\Models\Sponsor');
    }

    public function organizer(){
        return $this->belongsTo('App\User');
    }

    public function extras(){
        return $this->hasMany('App\Models\Extra');
    }

    public function attendees(){
        return $this->belongsToMany('App\User', 'tickets')->withPivot('code')->withTimestamps();
    }

    public static function validate($data) {
        return Validator::make($data, [
            'name' => 'required|max:100',
            'starts' => 'required|date|before:end',
            'end' => 'required|date|after:starts',
            'registration_start' => 'required|date|before:starts|before:end|before:registration_end',
            'registration_end' => 'required|date|before:starts|before:end|after:registration_start',
            'image' => 'max:100',
            'description' => 'max:1000',
            'organizer_id' => 'required|exists:users,id',
            'guest_capacity' => 'required|min:1',
            'event_type' => 'required'
        ]);
    }
}
