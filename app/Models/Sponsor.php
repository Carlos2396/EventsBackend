<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Sponsor extends Model
{

    protected $fillable = ['name', 'image', 'event_id'];

    const relations = ['events'];
    const redis_title = 'sponsors';

    public function events(){
        return $this->belongsTo('App\Models\Event');
    }

    public static function validate($data) {
        return Validator::make($data, [
            'name' => 'required|max:500',
            'image' => 'nullable|max:100',
            'event_id' => 'required|exists:events,id'
        ]);
    }
}
