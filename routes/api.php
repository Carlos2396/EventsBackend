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
     * Unprotected routes
     */
    Route::post('users', 'UserController@store')->name('users.register');

    
    /**
     * Auth protected routes
     */
    Route::group(['middleware' => 'auth:api'], function() {
        
        // User routes
        Route::get('user', 'UserController@loggedUser')->name('users.logged');
        Route::put('user/changePassword', 'UserController@changeLoggedPassword')->name('users.logged.changePassword');
        Route::put('user', 'UserController@updateLogged')->name('users.logged.update');
        Route::delete('user', 'UserController@destroyLogged')->name('users.logged.delete');
    });

    /**
     * Auth and Admin protected routes
     */
    Route::group(['middleware' => ['auth:api', 'role:admin']], function() {
        
        /**
         * User routes
         */
        Route::get('users', 'UserController@index')->name('users.list'); 
        Route::get('users/{user}', 'UserController@show')->name('users.show');
        Route::put('users/{user}/changePassword', 'UserController@changePassword')->name('users.changePassword');
        Route::put('users/{user}', 'UserController@update')->name('users.update');
        Route::delete('users/{user}', 'UserController@destroy')->name('users.delete');
    });

    /**
     * Extras routes
     */
    Route::get('extras', 'ExtraController@index')->name('extras.list'); 
    Route::post('extras', 'ExtraController@store')->name('extras.store');
    Route::put('extras/{extra}', 'ExtraController@update')->name('extras.update');
    Route::delete('extras/{extra}', 'ExtraController@destroy')->name('extras.delete');
    Route::get('extras/{extra}', 'ExtraController@show')->name('extras.show');

    /**
     * Authentication routes
     */
    Route::group(['namespace' => 'Auth'], function() {
        Route::post('login', 'AuthController@login')->name('login');
    
        Route::group(['middleware' => 'auth:api'], function() { 
            Route::get('logout', 'AuthController@logout')->name('logout');
            Route::get('logged', 'AuthController@loggedUSer')->name('logged');
        });
    });

    /**
     * Articles routes
     */
    Route::get('articles', 'ArticleController@index')->name('articles.list'); 
    Route::post('articles', 'ArticleController@store')->name('articles.store');
    Route::put('articles/{article}', 'ArticleController@update')->name('articles.update');
    Route::delete('articles/{article}', 'ArticleController@destroy')->name('articles.delete');
    Route::get('articles/{article}', 'ArticleController@show')->name('articles.show');

    /**
     * Sponsor routes
     */
    Route::get('sponsors', 'SponsorController@index')->name('sponsors.list'); 
    Route::post('sponsors', 'SponsorController@store')->name('sponsors.store');
    Route::put('sponsors/{sponsor}', 'SponsorController@update')->name('sponsors.update');
    Route::delete('sponsors/{sponsor}', 'SponsorController@destroy')->name('sponsors.delete');
    Route::get('sponsors/{sponsor}', 'SponsorController@show')->name('sponsors.show');
    
  /*
     * Events routes
     */
    Route::get('events', 'EventController@index')->name('events.list'); 
    Route::post('events', 'EventController@store')->name('events.store');
    Route::put('events/{event}', 'EventController@update')->name('events.update');
    Route::delete('events/{event}', 'EventController@destroy')->name('events.delete');
    Route::get('events/{event}', 'EventController@show')->name('events.show');
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