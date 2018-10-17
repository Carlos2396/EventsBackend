<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    public function events(){
        return $this->belongsTo('App\Models\Event');
    }
}
