<?php

namespace App\Http\Controllers\API;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper; 
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = json_decode(Redis::get(Event::redis_title));

        if($events == null) {
            $events = Event::with(Event::relations)->get()->sortByDesc('created_at');
            Redis::setex(Event::redis_title, 60*10, $events);
        }   

        return response()->json($events, 200);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Event::validate($request->all());
        if($validator->fails()){
            return ResponseHelper::validationErrorResponse($validator->errors());
        }
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {   
        return response()->json(Event::with(Event::relations)->get()->find($event->id), 200);
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $validator = Event::validate($request->all());

        if($validator->fails()){
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $event->update($request->all());

        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {

        $event->delete();
        return response()->json($event, 204);
    }
}
