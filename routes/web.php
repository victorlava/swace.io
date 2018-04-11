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

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

Route::resource('/contribute', 'ContributeController')->only(['create', 'store']);
Route::post('/login', 'Auth\LoginController@authenticate')->name('login.auth');
