<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    public function event(){
        return $this->belongsTo('App\Models\Event');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User', 'answers')->withPivot('answer');
    }
}
