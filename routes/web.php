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

Route::get('/users/index', 'UserController@index');

Route::post('/users/register', 'UserController@create');

Route::put('/users/login', 'UserController@login');

Route::put('/users/logout/{token?}', 'UserController@logout');

Route::get('/categories/index/{id?}', 'CategoryController@index');

Route::post('/categories/create', 'CategoryController@create');

Route::put('/categories/update/{id}', 'CategoryController@update');

Route::delete('/categories/delete/{id}', 'CategoryController@delete');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
