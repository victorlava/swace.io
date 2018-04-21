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
Route::get('/payment/callback/{token}', 'PaymentController@callback')->name('payment.callback'); // Callback for Coingate - must be public, no auth here
Route::get('/payment/success/{order_id}/{token_amount}', 'PaymentController@success')->name('payment.success')->middleware('verified');
Route::get('/payment/cancel/{order_id}/{token_amount}', 'PaymentController@cancel')->name('payment.cancel')->middleware('verified');
