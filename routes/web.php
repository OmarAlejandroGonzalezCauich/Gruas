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

Route::get('/', function () {
    return view('welcome');
});
Route::resource('api/users', 'UserController');
Route::resource('api/brands', 'BrandController');
Route::resource('api/colors', 'ColorController');
Route::resource('api/cars', 'CarController');
Route::resource('api/typecars', 'TypeCarController');
// Loguear al usuario
Route::post('/api/login', 'UserController@login');
// Vehículos por usuario
Route::get('/api/carsByUser', 'CarController@carsByUser');
