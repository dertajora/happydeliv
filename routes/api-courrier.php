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

Route::group(['middleware' => ['log_api']], function () {
	Route::post('/login', 'APICourrier\UserController@login');
});

Route::group(['middleware' => ['check_param', 'log_api']], function () {
    Route::post('/user_information', 'APICourrier\UserController@user_information');
    Route::post('/add_package', 'APICourrier\PackageController@add_package');
    Route::post('/list_package', 'APICourrier\PackageController@list_package');
    Route::post('/detail_package', 'APICourrier\PackageController@detail_package');
    Route::post('/set_destination_package', 'APICourrier\PackageController@set_destination');

    Route::post('/process_package', 'APICourrier\PackageController@process_package');
    Route::post('/finish_package', 'APICourrier\PackageController@finish_package');
    Route::post('/list_history', 'APICourrier\PackageController@list_history');
    
});