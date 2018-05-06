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

Route::post('/payment/callback/{hash}', 'PaymentController@callback')->name('payment.callback'); // Public for coingate response
Route::prefix('payment')->name('payment.')->middleware('verified')->group(function () {
    Route::post('/', 'PaymentController@store')->name('store');
    Route::get('/success/{order_id}', 'PaymentController@success')->name('success');
    Route::get('/cancel/{order_id}', 'PaymentController@cancel')->name('cancel');
});

Route::prefix('adm1n')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('index');
    Route::get('/transactions', 'Admin\TransactionController@index')->name('transactions.index');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('', 'Admin\UserController@index')->name('index');
        Route::get('/filter', 'Admin\UserController@filter')->name('filter');
        Route::get('/{user_id}/log', 'Admin\UserController@log')->name('log');
        Route::get('/{user_id}/transaction', 'Admin\UserController@transaction')->name('transaction');
        Route::post('/export', 'Admin\UserController@export')->name('export');
    });
});
