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

Route::get('/', 'DashboardController@index')->name('dashboard.index')->middleware('verified.message');
Route::get('/api/rates/{currency}', 'DashboardController@json_rates')->name('dashboard.json.rates');

Route::resource('/contribute', 'ContributeController')->only(['create', 'store'])->middleware('guest');

Route::prefix('profile')->name('profile.')->middleware('verified.message')->group(function () {
    Route::get('', 'ProfileController@index')->name('index');
    Route::post('/store', 'ProfileController@store')->name('store');

    Route::get('/password', 'PasswordController@index')->name('password.index');
    Route::post('/password/store', 'PasswordController@store')->name('password.store');
});

Route::prefix('kyc')->name('kyc.')->middleware('verified')->group(function () {
  Route::get('/', 'KycController@index')->name('index');
  Route::get('/status', 'KycController@status')->name('status');
});
Route::get('/kyc/callback', 'KycController@callback')->name('kyc.callback');

Auth::routes();
Route::post('/login', 'Auth\LoginController@authenticate')->name('login.auth');
Route::get('/email/verify/{token}', 'Auth\RegisterController@verify')->name('email.verification');

Route::prefix('payment')->name('payment.')->middleware('verified')->group(function () {
    Route::post('/', 'PaymentController@store')->name('store');
    Route::get('/success/{id}', 'PaymentController@success')->name('success');
    Route::get('/cancel/{id}', 'PaymentController@cancel')->name('cancel');
});
Route::post('/payment/callback', 'PaymentController@callbackNoHash')
    ->name('payment.callback_no_hash');

Route::post('/payment/callback/{hash}', 'PaymentController@callback')
    ->name('payment.callback');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('index');
    Route::get('/transactions', 'Admin\TransactionController@index')->name('transactions.index');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('', 'Admin\UserController@index')->name('index');
        Route::get('/filter', 'Admin\UserController@filter')->name('filter');
        Route::get('/{user}/log', 'Admin\UserController@log')->name('log');
        Route::get('/{user}/transaction', 'Admin\UserController@transaction')->name('transaction');
        Route::post('/export', 'Admin\UserController@export')->name('export');
    });
});
