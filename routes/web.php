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
// alias in route login should be defined, so auth middleware could detect which login page user should be redirected when not logged in but trying to force dashboard
Route::get('/login',  [ 'as' => 'login', 'uses' => 'WebsiteController@login_page']);
Route::get('/home', 'WebsiteController@landing_page');

Route::post('/login', 'WebsiteController@login_handle');
Route::post('/register', 'WebsiteController@register_handle');

Route::get('/logout', 'Dashboard\DashboardController@logout');

Route::group(['middleware' => ['auth']], function () {
   	Route::get('/dashboard', 'Dashboard\DashboardController@home');
   	
   	Route::get('/manage_employees', 'Dashboard\EmployeeController@home');
   	Route::get('/manage_employees/add', 'Dashboard\EmployeeController@add');
   	Route::post('/manage_employees/save', 'Dashboard\EmployeeController@save');

   	Route::get('/manage_packages', 'Dashboard\PackageController@home');
   	Route::get('/manage_packages/add', 'Dashboard\PackageController@add');
   	Route::post('/manage_packages/save', 'Dashboard\PackageController@save');
});