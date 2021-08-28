<?php

use Illuminate\Support\Facades\Route;

Route::middleware('cors:api')->group(function (){
    
Route::middleware('guest:api')->group(function () {
    Route::post('/login','AuthController@login');
	Route::post('/register','AuthController@register');

	Route::prefix('admin')->namespace('admin')->group(function(){
		Route::post('/login','AuthController@login');
		Route::post('/register','AuthController@register');
	});
});

Route::middleware('auth:api')->group(function () {
	Route::prefix('user')->group(function () {
		Route::post('/phone','UserController@AddPhone');
		Route::get('/orders','UserController@GetOrders');
		Route::put('/phone/{id}','UserController@UpdatePhone');
		Route::delete('/phone/{id}','UserController@DeletePhone');
		Route::put('/','UserController@update');
		Route::get('/','UserController@get');
	});
	Route::prefix('admin')->group(function(){
		Route::prefix('user')->group(function () {
			Route::post('/phone','UserController@AddPhone');
			Route::get('/orders','UserController@GetOrders');
			Route::put('/phone/{id}','UserController@UpdatePhone');
			Route::delete('/phone/{id}','UserController@DeletePhone');
			Route::put('/','UserController@update');
			Route::get('/','UserController@get');
		});
	});

	Route::prefix('admin')->namespace('admin')->group(function(){
		Route::get('/groups','GlobalController@groups');
		Route::get('/banners','GlobalController@banners');
		Route::post('/banners/create/{type}','GlobalController@createBanner');
		Route::post('/banners/edit/{id}','GlobalController@editBanner');
		Route::delete('/delete/{table}/{id}','GlobalController@delete');
		Route::prefix('settings')->group(function () {
			Route::get('/','GlobalController@getSettings');
			Route::post('/edit/{key}','GlobalController@updateSetting');
			Route::get('/find/{key}','GlobalController@findSetting');
		});
		Route::prefix('products')->group(function () {
			Route::get('/','ProductController@get');
			Route::post('/edit/{id}','ProductController@upload');
		});
		Route::prefix('user')->group(function () {
			Route::get('/list/{type}','UserController@list');
		});
		Route::prefix('orders')->group(function () {
			Route::get('/list','OrderController@list');
		});
	});
	
	// Route::delete('/address/{id}/main','AddressController@setMain')->name('api.address.main');
    });
});
Route::prefix('settings')->group(function () {
	Route::get('/','GlobalController@getSettings');
	Route::get('/{key}','GlobalController@findSetting');
});
Route::post('/logout','AuthController@logout');
Route::middleware('auth:api')->prefix('address')->group(function () {
	Route::post('/','AddressController@create');
	Route::put('/{id}','AddressController@update');
	Route::delete('/{id}','AddressController@delete');
	Route::get('/list','AddressController@list');
	Route::get('/find/{id}','AddressController@find');
});
Route::prefix('area')->group(function () {
	Route::post('/','AreaController@create');
	Route::put('/{id}','AreaController@update');
	Route::delete('/{id}','AreaController@delete');
	Route::get('/list','AreaController@list');
});

Route::prefix('group')->group(function () {
	Route::post('/','GroupController@create');
	Route::put('/{id}','GroupController@update');
	Route::put('/disable/{id}','GroupController@disable');
	Route::put('/enable/{id}','GroupController@enable');
	Route::delete('/{id}','GroupController@delete');
	Route::get('/find/{id}','GroupController@find');
	Route::get('/list','GroupController@list');
	Route::get('/two-layers','GroupController@listWithChildren');
	Route::get('/three-layers','GroupController@listThreeLayers');
});
Route::prefix('product')->group(function () {
	Route::post('/','ProductController@create');
	Route::put('/{id}','ProductController@update');
	Route::delete('/{id}','ProductController@delete');
	Route::get('/find/{id}','ProductController@find');
	Route::get('/','ProductController@list');
	Route::get('/home/{key}','ProductController@listHome');
});

Route::prefix('cart')->middleware('auth:api')->group(function(){
	Route::get('/','CartController@get');
	Route::get('/totals','CartController@getTotals');
	Route::post('/','CartController@create');
	Route::delete('/{id}','CartController@delete');
	Route::put('/{id}','CartController@update');
	Route::post('/coupon','CartController@applyCoupon');
	Route::put('/address/{id}','CartController@applyAddress');
	Route::post('/checkout','CartController@checkout');
// Route::delete('/decrease/{id}','CartController@DecreaseCartItem');
	
});
Route::prefix('authors')->group(function(){
	Route::get('/','GlobalController@listAuthors');
// Route::delete('/decrease/{id}','CartController@DecreaseCartItem');
});
Route::prefix('wishlist')->middleware('auth:api')->group(function(){
	Route::get('/','WishlistController@get');
	Route::post('/','WishlistController@create');
	Route::post('/switch/{id}','WishlistController@switch');
	Route::delete('/{id}','WishlistController@delete');
// Route::delete('/decrease/{id}','CartController@DecreaseCartItem');
	
});



Route::prefix('banners')->group(function(){
	Route::get('/sliders','GlobalController@getSliders');
	Route::get('/home','GlobalController@getHomeBanners');
// Route::delete('/decrease/{id}','CartController@DecreaseCartItem');
	
});