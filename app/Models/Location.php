<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Location extends Model
{
    const relations = ['event'];
    const redis_title = 'locations';

    protected $fillable = ['event_id', 'name', 'address', 'lat', 'lng'];

    public static function validate($data) {
        return Validator::make($data, [
            'event_id' => 'present|required|exists:events,id',
            'name' => 'present|required|string',
            'address'=>'present|required',
            'lat' => 'present|required|numeric|max:90|min:-90',
            'lng' => 'present|required|numeric|max:180|min:-180'
        ]);
    }

    public function event(){
        return $this->belongsTo('App\Models\Event');
    }
}
