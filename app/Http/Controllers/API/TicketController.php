<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\Models\Event;


class TicketController extends Controller
{
    /**
    *List all tickets of a user
    */
    public function index(User $user){
        $events = $user->events;
        return response()->json($events, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'present|required|numeric|exists:events,id',
            'user_id' => 'present|required|numeric|exists:users,id'
        ]);

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::find($request->user_id);
        $event = Event::find($request->event_id);

        $user->events()->detach($event->id);
        
        $user->events()->attach($event->id, ['code' => ''.$user->id.$event->id]);

        return response()->json($user->events->find($event->id)->pivot, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Event $event)
    {
        $user->events()->detach($event->id);

        return response()->json($user, 204);
    }
}
