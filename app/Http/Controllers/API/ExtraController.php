<?php

namespace App\Http\Controllers\API;

use App\Models\Extra;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extras = Extra::with(Extra::relations)->get();
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
        $extra = $extra->with(Extra::relations)->get();
        return response()->json($extra->first(), 200);
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
