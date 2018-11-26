<?php

namespace App\Http\Controllers\API;

use App\Models\Extra;
use App\Models\Event;
use App\User;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extras = json_decode(Redis::get(Extra::redis_title));

        if($extras == null) {
            $extras = Extra::with(Extra::relations)->get()->sortByDesc('created_at');
            Redis::setex(Extra::redis_title, 60*10, $extras);
        }
        
        return response()->json($extras, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Extra::validate($request->all());

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $extra = Extra::create($request->all());

        return response()->json($extra, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function show(Extra $extra)
    {
        $extra = Extra::with(Extra::relations)->get()->find($extra->id);
        return response()->json($extra, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extra $extra)
    {
        $validator = Extra::validate($request->all());

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $extra->update($request->all());

        return response()->json($extra, 200);
    }

    /*
    public function answer(int $extra_id, int $user_id){
        $extra = Extra::find($extra_id);
        $result = $extra->users->where('id', $user_id)[0];
        return response()->json($result, 200);
    }*/
    

    public function individualAnswers($event_id, $user_id){
        $event = Event::find($event_id);
        $extra_ids = $event->extras;
        $index = 0;

        $results = [];

        for($index = 0; $index < count($extra_ids); $index+=1){
            $extra = Extra::find($extra_ids[$index]->id);
            $answer = $extra->users->where('id', $user_id)[0];
            $results[$index] = ($answer)->pivot->answer;
        }

        return response()->json($results, 200);
    }

    public function generalAnswers($event_id){
        $event = Event::find($event_id);
        $extra_ids = $event->extras;
        $answers;
        $index = 0;
        $index2 = 0;

        for($index = 0; $index < count($extra_ids); $index+=1){
            $extra = Extra::find($extra_ids[$index]->id);
            for($index2 = 0; $index2 < count($extra->users); $index2+=1){
                $answers[$index][$index2] = $extra->users[$index2]->pivot->answer;
            }
        }

        return response()->json($answers, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Extra $extra)
    {
        $extra->delete();
        return response()->json(null, 204);
    }
}
