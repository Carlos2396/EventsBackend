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
        // Forget password routes
        Route::post('password/create', 'PasswordResetController@create')->name('password.reset.create');
        Route::post('password/reset', 'PasswordResetController@reset')->name('password.reset');

        // Email confirmation routes
        Route::get('confirm/{uuid}', 'AccountConfirmationController@confirmAccount')->name('confirmation');
        Route::get('resend/{email}', 'AccountConfirmationController@resendConfirmationEmail')->name('confirmation.resend');
    
        // Session routes
        Route::post('login', 'AuthController@login')->name('login');
        Route::group(['middleware' => 'auth:api'], function() { 
            Route::get('logout', 'AuthController@logout')->name('logout');
            Route::get('logged', 'AuthController@loggedUSer')->name('logged');
        });
    });
    
    /**
     * Unprotected routes
     */
    Route::post('users', 'UserController@store')->name('users.register');
    
    /**
     * Auth protected routes
     */
    Route::group(['middleware' => 'auth:api'], function() {
        
        Route::get('user', 'Auth\AuthController@loggedUser')->name('users.logged');
        Route::put('user/changePassword', 'UserController@changeLoggedPassword')->name('users.logged.changePassword');
        Route::put('user', 'UserController@updateLogged')->name('users.logged.update');
        Route::delete('user', 'UserController@destroyLogged')->name('users.logged.delete');

        /**
         * Extras routes
         */
        Route::get('extras', 'ExtraController@index')->name('extras.list'); 
        Route::post('extras', 'ExtraController@store')->name('extras.store');
        Route::put('extras/{extra}', 'ExtraController@update')->name('extras.update');
        Route::delete('extras/{extra}', 'ExtraController@destroy')->name('extras.delete');
        Route::get('extras/{extra}', 'ExtraController@show')->name('extras.show');

        /**
         * Answers routes
         */
        Route::post('answers', 'AnswerController@store')->name('answers.store');
        Route::put('answers/{answer}', 'AnswerController@update')->name('answers.update');

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
        

        /**
         * Ticket routes
         */
        Route::post('tickets', 'TicketController@store')->name('tickets.store');
        Route::delete('users/{user}/events/{event}', 'TicketController@destroy')->name('tickets.delete');

        /**
         * Location routes
         */
        Route::get('locations', 'LocationController@index')->name('locations.list'); 
        Route::post('locations', 'LocationController@store')->name('locations.store');
        Route::put('locations/{location}', 'LocationController@update')->name('locations.update');
        Route::delete('locations/{location}', 'LocationController@destroy')->name('locations.delete');
        Route::get('locations/{location}', 'LocationController@show')->name('locations.show');

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