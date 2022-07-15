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
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/appointments', 'AppointmentController@index')->name('appointments.index');
Route::get('/appointments/new', 'AppointmentController@create')->name('appointments.create');
Route::post('/appointments/store', 'AppointmentController@store')->name('appointments.store');
Route::get('/appointments/{appointment}', 'AppointmentController@show')->name('appointments.show');
