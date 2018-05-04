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

Auth::routes();
Route::get('/email/verify/{token}', 'Auth\RegisterController@verify')->name('email.verification');

Route::get('/', 'PageController@index')->name('page.landing');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index')->middleware('verified.message');
Route::get('/dashboard/buy-tokens', 'DashboardController@create')->name('dashboard.create')->middleware('verified');

Route::resource('/contribute', 'ContributeController')->only(['create', 'store'])->middleware('guest');

Route::post('/login', 'Auth\LoginController@authenticate')->name('login.auth');

Route::post('/payment', 'PaymentController@store')->name('payment.store')->middleware('verified');
Route::post('/payment/callback/{hash}', 'PaymentController@callback')->name('payment.callback'); // Callback for Coingate - must be public, no auth here
Route::get('/payment/success/{order_id}', 'PaymentController@success')->name('payment.success')->middleware('verified');
Route::get('/payment/cancel/{order_id}', 'PaymentController@cancel')->name('payment.cancel')->middleware('verified');



Route::get('/admin/transactions', 'Admin\TransactionController@index')->name('admin.transactions.index'); // Middleware admin

Route::get('/admin/users', 'Admin\UserController@index')->name('admin.users.index'); // Middleware admin
Route::get('/admin/users/{user_id}/log', 'Admin\UserController@log')->name('admin.users.log'); // Middleware admin
Route::get('/admin/users/{user_id}/transaction', 'Admin\UserController@transaction')->name('admin.users.transaction'); // Middleware admin
