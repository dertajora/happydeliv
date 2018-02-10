<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes for End User App
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'API\UserController@register');
Route::post('/login', 'API\UserController@login');
Route::post('/resend_otp', 'API\UserController@resend_otp');
Route::post('/verify_otp', 'API\UserController@verify_otp');


Route::group(['middleware' => ['check_param']], function () {
    Route::post('/user_information', 'API\UserController@user_information');
    Route::post('/track_package', 'API\TrackController@add_package');
    Route::post('/list_package', 'API\TrackController@list_package');
    Route::post('/list_history', 'API\TrackController@list_history');
    Route::post('/detail_package', 'API\TrackController@detail_package');
});