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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users/calendar', 'UserController@calendar')->name('users.calendar')->middleware('auth');
Route::get('/users/password/edit', 'UserController@edit_password')->name('users.edit_password')->middleware('auth');
Route::put('/users/update_password', 'UserController@update_password')->name('users.update_password')->middleware('auth');
Route::get('/users/record', 'UserController@seller_record')->name('users.seller_record')->middleware('auth');

Route::get('/customers', 'CustomerController@index')->name('customers.index')->middleware('auth');
Route::get('/customers/{customer}', 'CustomerController@show')->name('customers.show')->middleware('auth');
Route::get('/customers/{customer}/edit', 'CustomerController@edit')->name('customers.edit')->middleware('auth');
Route::put('/customers/{customer}/update', 'CustomerController@update')->name('customers.update')->middleware('auth');

Route::get('/appointments', 'AppointmentController@index')->name('appointments.index')->middleware('auth');
Route::get('/appointments/new', 'AppointmentController@create')->name('appointments.create')->middleware('auth');
Route::post('/appointments/store', 'AppointmentController@store')->name('appointments.store')->middleware('auth');
Route::get('/appointments/byday', 'AppointmentController@byday')->name('appointments.byday')->middleware('auth');
Route::get('/appointments/report', 'AppointmentController@report')->name('appointments.report')->middleware('auth');
Route::put('/appointments/change_status', 'AppointmentController@change_status')->name('appointments.change_status')->middleware('auth');
Route::get('/appointments/{appointment}', 'AppointmentController@show')->name('appointments.show')->middleware('auth');
Route::get('/appointments/{appointment}/edit', 'AppointmentController@edit')->name('appointments.edit')->middleware('auth');
Route::put('/appointments/{appointment}/update', 'AppointmentController@update')->name('appointments.update')->middleware('auth');
Route::delete('/appointments/{appointment}/destroy', 'AppointmentController@destroy')->name('appointments.destroy')->middleware('auth');

if (App::environment('production')) {
    URL::forceScheme('https');
}
