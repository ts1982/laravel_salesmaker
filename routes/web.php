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

Route::get('/web', 'WebController@index')->name('web.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sellers/calendar', 'UserController@seller_calendar')->name('users.seller_calendar')->middleware('auth');
Route::get('/appointers/calendar', 'UserController@appointer_calendar')->name('users.appointer_calendar')->middleware('auth');

Route::get('/users/password/edit', 'UserController@edit_password')->name('users.edit_password')->middleware('auth');
Route::put('/users/update_password', 'UserController@update_password')->name('users.update_password')->middleware('auth');
Route::get('/sellers/record', 'UserController@seller_record')->name('users.seller_record')->middleware('auth');
Route::get('/appointers/record', 'UserController@appointer_record')->name('users.appointer_record')->middleware('auth');

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

Route::get('/dashboard', 'AdminController@dashboard')->middleware('auth:admins');

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('login', 'Dashboard\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Dashboard\Auth\LoginController@login')->name('login');
    Route::resource('users', 'Dashboard\UserController')->middleware('auth:admins');
    Route::get('sellers/record', 'Dashboard\UserController@sellers_record')->name('users.sellers_record')->middleware('auth:admins');
    Route::get('appointers/record', 'Dashboard\UserController@appointers_record')->name('users.appointers_record')->middleware('auth:admins');
    Route::get('sellers', 'Dashboard\UserController@sellers_index')->name('users.sellers_index')->middleware('auth:admins');
    Route::get('appointers', 'Dashboard\UserController@appointers_index')->name('users.appointers_index')->middleware('auth:admins');
    Route::resource('customers', 'Dashboard\CustomerController')->middleware('auth:admins');
    Route::get('appointments/byday', 'Dashboard\AppointmentController@byday')->name('appointments.byday')->middleware('auth:admins');
    Route::get('appointments/holiday', 'Dashboard\HolidayController@holiday')->name('appointments.holiday')->middleware('auth:admins');
    Route::post('appointments/holiday/store', 'Dashboard\HolidayController@holiday_store')->name('appointments.holiday_store')->middleware('auth:admins');
    Route::resource('appointments', 'Dashboard\AppointmentController')->middleware('auth:admins');
    Route::get('records/sellers', 'Dashboard\RecordController@sellers')->name('records.sellers')->middleware('auth:admins');
    Route::get('records/appointers', 'Dashboard\RecordController@appointers')->name('records.appointers')->middleware('auth:admins');
    Route::get('records/incentive', 'Dashboard\RecordController@incentive')->name('records.incentive')->middleware('auth:admins');
});

if (App::environment('production')) {
    URL::forceScheme('https');
}
