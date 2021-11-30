<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::group(
    [
        'prefix' => 'auth',
        'as' => 'oauth.',
        'middleware' => 'guest',
        'namespace' => 'Auth',
    ],
    function () {
        Route::get(
            '{driver}',
            ['as' => 'login', 'uses' => 'LoginController@loginWith']
        )->where(['type' => '(twitter|facebook)']);

        // ANY CHANGES HERE REQUIRE AN UPDATE IN config/services.php
        Route::get(
            '{driver}/callback',
            ['as' => 'callback', 'uses' => 'LoginController@handleOauthCallback']
        )->where(['type' => '(twitter|facebook)']);
    }
);

Route::group(
    [
        'prefix' => 'location',
        'as' => 'location.',
    ],
    function () {
        Route::get('/', ['as' => 'index', 'uses' => 'LocationController@index']);
        Route::get('create', ['as' => 'create', 'uses' => 'LocationController@create']);
        Route::post('create', ['as' => 'store', 'uses' => 'LocationController@store']);
        Route::get('{hashid}/show', ['as' => 'show', 'uses' => 'LocationController@show']);
        Route::post('{hashid}/favourite', ['as' => 'favourite', 'uses' => 'LocationController@favourite']);
    }
);
