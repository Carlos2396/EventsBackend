<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'API'], function() {
    /**
     * Authentication routes
     */
    Route::group(['namespace' => 'Auth'], function() {
        Route::post('login', 'AuthController@login')->name('login');
    
        Route::group(['middleware' => 'auth:api'], function() { 
            Route::get('logout', 'AuthController@logout')->name('logout');
        });
    });


    Route::group(['middleware' => 'auth:api'], function() {
        /**
         * Ticket routes
         */
        Route::get('tickets', 'TicketController@index')->name('tickets.list'); 
        Route::post('tickets', 'TicketController@store')->name('tickets.store');
        Route::put('tickets/{ticket}', 'TicketController@update')->name('tickets.update');
        Route::delete('tickets/{ticket}', 'TicketController@destroy')->name('tickets.delete');
        Route::get('tickets/{ticket}', 'TicketController@show')->name('tickets.show');

        /**
         * Location routes
         */
        Route::get('locations', 'LocationController@index')->name('locations.list'); 
        Route::post('locations', 'LocationController@store')->name('locations.store');
        Route::put('locations/{location}', 'LocationController@update')->name('locations.update');
        Route::delete('locations/{location}', 'LocationController@destroy')->name('locations.delete');
        Route::get('locations/{location}', 'LocationController@show')->name('locations.show');
    });

    /**
     * Articles routes
     */
    Route::get('articles', 'ArticleController@index')->name('articles.list'); 
    Route::post('articles', 'ArticleController@store')->name('articles.store');
    Route::put('articles/{article}', 'ArticleController@update')->name('articles.update');
    Route::delete('articles/{article}', 'ArticleController@destroy')->name('articles.delete');
    Route::get('articles/{article}', 'ArticleController@show')->name('articles.show');
});

/**
 * Test routes, do not delete
 */
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('test/check', 'API\Auth\AuthController@check')->name('auth.check');

    Route::group(['middleware' => 'role:admin'], function () {       
        Route::get('test/admin/check', function() {
            return response(['success' => true], 200);
        })->name('admin.check');
    });
});

Route::any('{catchAll}', function($route) {
    return response()->json(['message' => 'Not found '.$route], 404);
});