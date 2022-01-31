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

Route::get("/", function(){
    return view("welcome");
 })->name('home');

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function ($router) {

    Route::group(['prefix' => 'admin'] , function ($router) {

        Route::get('register', 'AdminController@create')->name('admins.create');
        
        Route::post('register', 'AdminController@register')->name('admins.save');
        
        Route::get('login', 'AdminController@loginForm')->name('admins.loginform');
        
        Route::post('login', 'AdminController@login')->name('admins.login');

        Route::get('logout', 'AdminController@logout')->name('admins.logout');
        
        Route::get('home', 'AdminController@index')->name('admins.index');

        Route::post('send-message', 'MessageController@send')->name('admin.sendmessage');

        Route::post('chat', 'ChatRoomController@findChatRoom')->name('chat');

        Route::get('chat-room/{id}', 'MessageController@getMessages')->name('admin.getmessages');

    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function ($router) {

    Route::group(['prefix' => 'user'] , function ($router) {

        Route::get('register', 'UserController@create')->name('users.create');

        Route::post('register', 'UserController@register')->name('users.save');

        Route::get('login', 'UserController@loginForm')->name('users.loginform');

        Route::post('login', 'UserController@login')->name('users.login');

        Route::get('logout', 'UserController@logout')->name('users.logout');

        Route::get('home', 'UserController@index')->name('users.index');

        Route::post('chat', 'ChatRoomController@findChatRoom')->name('user.chat');

        Route::post('send-message', 'MessageController@send')->name('user.sendmessage');

        Route::get('chat-room/{id}', 'MessageController@getMessages')->name('user.getmessages');
    });
});





