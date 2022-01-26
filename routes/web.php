<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers'], function ($router) {

    Route::group(['prefix' => 'admin'] , function ($router) {

        Route::get('register', 'AdminController@create')->name('admin.create');

        Route::post('register', 'AdminController@register')->name('admin.save');

    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





