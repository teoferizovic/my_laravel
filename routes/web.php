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

Route::get('/users/index/{id?}', 'UserController@index');

Route::get('/users/indexQ/', 'UserController@indexQ');

Route::post('/users/register', 'UserController@create');

Route::post('/users/login', 'UserController@login');

Route::put('/users/logout/{token?}', 'UserController@logout');

Route::middleware(['check.auth','permissions'])->group(function () {

	//category routes
	Route::get('/categories/index/{id?}', 'CategoryController@index');

	//Route::post('/categories/create', 'CategoryController@create');

	//Route::put('/categories/update/{id}', 'CategoryController@update');
	Route::post('/categories/create', 'CategoryController@store');

	Route::put('/categories/update/{id}', 'CategoryController@store');

	Route::delete('/categories/delete/{id}', 'CategoryController@delete');

	//product routes
	Route::get('/products/index/{id?}', 'ProductController@index');
	//order routes
	Route::post('/orders/create', 'OrderController@create');
	Route::put('/orders/update/{id}', 'OrderController@update');
	Route::get('/orders/index', 'OrderController@index');


	//order_product routes
	Route::post('/order_products/create', 'OrderProductController@create');
});

Route::get('/orders/indexV', 'ProductViewController@index');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
