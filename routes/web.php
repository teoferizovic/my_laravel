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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::post('/users/register', 'UserController@create');

Route::post('/users/login', 'UserController@login');

Route::put('/users/logout/{token?}', 'UserController@logout');

Route::put('/users/forgot_password/', 'UserController@forgot_password');

Route::put('/users/reset_password/', 'UserController@reset_password');

Route::get('/roles/index/{id?}', 'RoleController@index');

Route::get('/users/actives/{id?}', 'UserController@activeUsers');

Route::get('/products/index/{id?}', 'ProductController@index');

Route::middleware(['check.auth','permissions'])->group(function () {

	Route::get('/users/index/{id?}', 'UserController@index');

	Route::get('/users/indexQ/', 'UserController@indexQ');
	
	//category routes
	Route::get('/categories/index/{id?}', 'CategoryController@index');

	Route::post('/categories/create', 'CategoryController@create');

	Route::put('/categories/update/{id}', 'CategoryController@update');

	Route::delete('/categories/delete/{id}', 'CategoryController@delete');

	//product routes
	//Route::get('/products/index/{id?}', 'ProductController@index');
	
	//order routes
	Route::post('/orders/create', 'OrderController@create');

	Route::put('/orders/update/{id}', 'OrderController@update');
	
	Route::get('/orders/index', 'OrderController@index');
	
	Route::get('/orders/index1', 'OrderController@index1');

	Route::get('/orders/users/{id}', 'OrderController@orders_users');

	//order_product routes
	Route::post('/order_products/create', 'OrderProductController@create');

	//comments routes
	Route::get('/comments/index/{id?}', 'CommentController@index');

	Route::post('/comments/create', 'CommentController@create');

	Route::delete('/comments/delete/{id}', 'CommentController@delete');

	Route::put('/comments/update/{id}', 'CommentController@update');

});

Route::get('/orders/indexV', 'ProductViewController@index');

Route::get('/orders/checkout/{id}', 'OrderController@checkout');

Route::post('/payments/create', 'PaymentController@create');

Route::post('/forders/create', 'FOrderController@create');

//region routes
Route::get('/regions/index/{id?}', 'RegionController@index');

Route::post('/regions/create', 'RegionController@create');

Route::delete('/regions/delete/{id}', 'RegionController@delete');

Route::put('/regions/update/{id}', 'RegionController@update');

//message routes
Route::get('/messages/index/{id?}', 'MessageController@index');

Route::post('/messages/create', 'MessageController@create');

Route::post('/messages/draft', 'MessageController@createDraft');

Route::delete('/messages/delete/{id}', 'MessageController@delete');

Route::put('/messages/update/{id}', 'MessageController@update');


Route::get('/home', 'HomeController@index')->name('home');
