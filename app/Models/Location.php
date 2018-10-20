<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Location extends Model
{
    protected $fillable = ['event_id', 'name', 'address', 'lat', 'lng'];

    public static function validate($data) {
        return Validator::make($data, [
            'event_id' => 'present|required|exists',
            'name' => 'present|required|string',
            'address'=>'present|required',
            'lat' => 'present|required|numeric',
            'lng' => 'present|required|numeric'
        ]);
    }

    public function events(){
        return $this->belongsTo('App\Models\Event');
    }
}
