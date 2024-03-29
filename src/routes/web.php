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

Route::get('/welcome', 'WebController@welcome')->name('welcome');

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
Route::put('/appointments/date_update', 'AppointmentController@date_update')->name('appointments.date_update')->middleware('auth');
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
    Route::post('logout', 'Dashboard\Auth\LoginController@logout')->name('logout');
    Route::get('seller/calendar', 'Dashboard\CalendarController@seller_calendar')->name('calendar.seller_calendar')->middleware('auth:admins');
    Route::get('appointer/calendar', 'Dashboard\CalendarController@appointer_calendar')->name('calendar.appointer_calendar')->middleware('auth:admins');
    Route::put('users/{user}/term_update', 'Dashboard\UserController@term_update')->name('users.term_update')->middleware('auth:admins');
    Route::get('users/reset_customers', 'Dashboard\UserController@reset_customers')->name('users.reset_customers')->middleware('auth:admins');
    Route::get('users/sellers/config', 'Dashboard\UserController@sellers_config')->name('users.sellers_config')->middleware('auth:admins');
    Route::get('users/appointers/config', 'Dashboard\UserController@appointers_config')->name('users.appointers_config')->middleware('auth:admins');
    Route::resource('users', 'Dashboard\UserController')->middleware('auth:admins');
    Route::put('users/{user}/join', 'Dashboard\UserController@join')->name('users.join')->middleware('auth:admins');
    Route::get('sellers/record', 'Dashboard\UserController@sellers_record')->name('users.sellers_record')->middleware('auth:admins');
    Route::get('appointers/record', 'Dashboard\UserController@appointers_record')->name('users.appointers_record')->middleware('auth:admins');
    Route::get('sellers', 'Dashboard\UserController@sellers_index')->name('users.sellers_index')->middleware('auth:admins');
    Route::get('appointers', 'Dashboard\UserController@appointers_index')->name('users.appointers_index')->middleware('auth:admins');
    Route::get('customers/replace', 'Dashboard\CustomerController@replace')->name('customers.replace')->middleware('auth:admins');
    Route::get('customers/replace/view', 'Dashboard\CustomerController@replace_view')->name('customers.replace_view')->middleware('auth:admins');
    Route::put('customers/replace/store', 'Dashboard\CustomerController@replace_store')->name('customers.replace_store')->middleware('auth:admins');
    Route::resource('customers', 'Dashboard\CustomerController')->middleware('auth:admins');
    Route::get('appointments/byday', 'Dashboard\AppointmentController@byday')->name('appointments.byday')->middleware('auth:admins');
    Route::get('appointments/holiday', 'Dashboard\HolidayController@holiday')->name('appointments.holiday')->middleware('auth:admins');
    Route::post('appointments/holiday/store', 'Dashboard\HolidayController@holiday_store')->name('appointments.holiday_store')->middleware('auth:admins');
    Route::resource('appointments', 'Dashboard\AppointmentController')->middleware('auth:admins');
    Route::get('records/sellers', 'Dashboard\RecordController@sellers')->name('records.sellers')->middleware('auth:admins');
    Route::get('records/appointers', 'Dashboard\RecordController@appointers')->name('records.appointers')->middleware('auth:admins');
    Route::get('records/incentive', 'Dashboard\RecordController@incentive')->name('records.incentive')->middleware('auth:admins');
});

Route::get('encrypt', 'EncryptController@index')->name('encrypt.index');
Route::post('encrypt', 'EncryptController@encrypt')->name('encrypt');
Route::post('decrypt', 'EncryptController@decrypt')->name('decrypt');

if (App::environment('production')) {
    URL::forceScheme('https');
}

