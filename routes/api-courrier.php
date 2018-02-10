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

Route::post('/login', 'APICourrier\UserController@login');

Route::group(['middleware' => ['check_param']], function () {
    Route::post('/user_information', 'APICourrier\UserController@user_information');
    Route::post('/add_package', 'APICourrier\PackageController@add_package');
    
});