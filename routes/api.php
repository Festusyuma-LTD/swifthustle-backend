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
   Route::get('my-data', function(){
      echo "super admin";
   });

    Route::post('add-admin', 'Auth\RegisterController@create');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api','isAdmin']], function() {
    Route::get('my-data', function(){
        echo "admin";
    });
    Route::resource('valid-games', 'ValidGameController');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api','isUser']], function() {
    Route::get('my-data', function(){
        echo "user";
    });
});



Route::post('/user/register', 'Auth\RegisterController@create');
Route::post('/user/login', 'Auth\LoginController@login');
Route::post('/user/reset-password', 'Auth\ResetPasswordController@resetPassword');

