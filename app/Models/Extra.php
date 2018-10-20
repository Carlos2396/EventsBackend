<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Extra extends Model
{
    protected $fillable = ['text', 'event_id'];

    public static function validate($data) {
        return Validator::make($data, [
            'text' => 'required|max:80',
            'event_id' => 'required|exists:events,id'
        ]);
    }

    public function event(){
        return $this->belongsTo('App\Models\Event');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User', 'answers')->withPivot('answer');
    }
}
