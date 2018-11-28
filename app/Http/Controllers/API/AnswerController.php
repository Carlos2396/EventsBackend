<?php

namespace App\Http\Controllers\API;

use App\Models\Answer;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Validator;

use App\User;
use App\Models\Extra;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required | exists:users,id',
            'extra_id' => 'required | exists:extras,id',
            'answer' => 'required | max: 1000'
        ]);

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::find($request->user_id);
        $extra = Extra::find($request->extra_id);

        $user->extras()->attach($extra->id, ['answer'=>$request->answer]);

        return response()->json($user->extras->find($extra->id)->pivot, 201);
    }

    /**
     * Store an array of newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMany(Request $request)
    {
        $currentRequest = $request->all()['arr'];

        foreach($currentRequest as $current){
            $validator = Validator::make($current, [
                'user_id' => 'required | exists:users,id',
                'id' => 'required | exists:extras,id',
                'answer' => 'required | max: 1000'
            ]);

            if($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator->errors());
            }
        }

        $user = User::find($currentRequest[0]['user_id']);

        foreach($currentRequest as $current){
            $user->extras()->attach($current['id'], ['answer'=>$current['answer']]);
        }

        return response()->json($user->extras->where('event_id', $currentRequest[0]['event_id']), 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Extra $extra, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answer' => 'required | max: 1000'
        ]);

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        if($user->events->find($extra->event_id) == null) {
            return ResponseHelper::validationErrorResponse([
                'user_id' => ['The user is not registered in this event.']
            ]);
        }

        $user->extras->find($extra->id)->pivot->answer = $request->answer;
        $user->extras->find($extra->id)->pivot->save();

        return response()->json($user->extras->find($extra->id)->pivot, 200);
    }
}
