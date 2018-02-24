<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes for Courrier Android App
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

Route::post('/get_token', 'APIPublic\GeneralController@get_token');

Route::group(['middleware' => ['check_auth', 'log_api']], function () {
    Route::post('/add_package', 'APIPublic\PackageController@add_package');
});
    