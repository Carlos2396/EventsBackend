<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function events(){
        return $this->belongsTo('App\Models\Event');
    }
}
