<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'super-admin', 'middleware' => ['auth:api','isSuperAdmin']], function() {
    Route::post('add-admin', 'Auth\RegisterController@create');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api','isAdmin']], function() {
    Route::post('valid-games', 'ValidGameController@store');
    Route::post('is-game-active', 'ValidGameController@isGameActive');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api','isUser']], function() {
    Route::get('my-data', function(){
        echo "user";
    });
    Route::post('join-game', 'User\GameController@joinGame');

    Route::group(['prefix' => 'game'], function () {
        Route::get('{id}', 'User\GameController@index');
        Route::get('{id}/slots', 'User\PlayController@userGameSlots');
        Route::post('select-position', 'User\PlayController@selectPosition');
        Route::get('{id}/play-time', 'User\PlayController@getPlayTime');
        Route::get('{id}/winner', 'User\PlayController@getWinner');
    });
});



Route::post('/user/register', 'Auth\RegisterController@create');
Route::post('/user/login', 'Auth\LoginController@login');
Route::post('/user/reset-password', 'Auth\ResetPasswordController@resetPassword');
