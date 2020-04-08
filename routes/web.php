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
    return view('welcome');
});

# user route (定义资源路由，restful)
Route::resource('user', 'User\UserController');
Route::post('/user/login', 'User\UserController@login')->name('user.login');
