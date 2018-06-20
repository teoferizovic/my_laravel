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

Route::post('/users/create', 'UserController@create');

Route::get('/categories/index/{id?}', 'CategoryController@index');

Route::post('/categories/create', 'CategoryController@create');

Route::put('/categories/update', 'CategoryController@update');

Route::delete('/categories/delete', 'CategoryController@delete');