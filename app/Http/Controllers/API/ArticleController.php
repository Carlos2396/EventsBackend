<?php

namespace App\Http\Controllers\API;

use DB;
use Redis;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $redis = Redis::Connection();

        $popular = $redis->zRevRange('articleViews', 0, -1);

        dd($popular);

        return response()->json(Article::all(), 200);
    }

    /**
     * Retrieves the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $redis = Redis::Connection();

        $redis->pipeline(function($pipe) use($article) {
            $pipe->incr('article:'.$article->id.':views');
            $pipe->zIncrBy('articleViews', 1, 'article:'.$article->id);
        });    

        $article->views = $redis->get('article:'.$article->id.':views');

        if($redis->zScore('articleViews','article:'.$article->id)) {
            $article->zviews = $redis->zScore('articleViews', 'article:'.$article->id);
        }

        return response()->json($article, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Article::validate($request->all());

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Article::validate($request->all());

        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $article->update($request->all());

        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(null, 204);
    }
}
