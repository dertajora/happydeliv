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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'WebsiteController@landing_page');
Route::get('/register', 'WebsiteController@registration_page');
Route::get('/login', 'WebsiteController@login_page');
Route::get('/home', 'WebsiteController@landing_page');

Route::post('/login', 'WebsiteController@login_handle');
Route::post('/register', 'WebsiteController@register_handle');

Route::group(['middleware' => ['auth']], function () {
   	Route::get('/dashboard', 'Dashboard\DashboardController@home');
});