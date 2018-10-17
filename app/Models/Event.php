<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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
}
