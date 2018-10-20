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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
