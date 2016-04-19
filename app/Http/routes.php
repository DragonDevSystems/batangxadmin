<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('uses' =>'HomeController@cusIndex', 'as' => 'cusIndex'));
Route::get('/about-us', array('uses' =>'HomeController@getAbout', 'as' => 'getAbout'));
Route::get('/new', array('uses' =>'HomeController@getNews', 'as' => 'getNews'));
Route::get('/contact-us', array('uses' =>'HomeController@getContactUs', 'as' => 'getContactUs'));
Route::group(array('prefix' => '/confirmation'),function()
{
	Route::get('/{code}/{id}', array('uses' => 'UserController@confirmation','as' => 'confirmation'));
});

Route::group(array('prefix' => '/admin'),function()
{
	Route::get('/', array('uses' =>'HomeController@index', 'as' => 'home'));

	Route::group(array('before' => 'guest'), function()
	{
		Route::get('/login',array('uses' =>'UserController@getLogin', 'as' => 'getLogin'));
		Route::group(array('before' => 'csrf'), function()
		{
			Route::post('/user/create', array('uses' => 'UserController@postCreate', 'as' => 'postCreate'));
			Route::post('/user/login',array('uses' => 'UserController@postLogin', 'as' => 'postLogin')); 
		});
	});

	Route::group(array('before' => 'auth'), function()
	{
		Route::get('/user/logout', array('uses' => 'UserController@getLogout', 'as' => 'getLogout'));
	});

	Route::group(array('prefix' => '/ajax'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::group(array('before' => 'csrf'), function()
			{
				Route::get('/statsbox', array('uses' => 'GlobalController@statsbox', 'as' => 'statsbox','middleware' => 'auth'));
				Route::get('/admin/list',array('uses' => 'UAMController@adminUserList', 'as' => 'adminUserList'));
				Route::get('/admin/uaal', array('uses' => 'UAMController@uaal', 'as' => 'uaal','middleware' => 'auth'));
				Route::get('/admin/information/{id}', array('uses' => 'GlobalController@userInfoList', 'as' => 'userInfoList','
					middleware' => 'auth'));
				Route::get('/admin/userlist', array('uses' => 'GlobalController@userlist', 'as' => 'userlist','
					middleware' => 'auth'));
				Route::get('/admin/accountAccessChecker/{event}', array('uses' => 'GlobalController@accountAccessChecker', 'as' => 'accountAccessChecker','
					middleware' => 'auth'));
				Route::get('/product/categoryList', array('uses' => 'FileMaintenanceController@categoryList', 'as' => 'categoryList','middleware' => 'auth'));
				Route::get('/product/categoryInfo/{cid}', array('uses' => 'GlobalController@categoryInfo', 'as' => 'categoryInfo','middleware' => 'auth'));

				Route::post('/product/addCategory', array('uses' => 'FileMaintenanceController@addCategory', 'as' => 'addCategory','middleware' => 'auth'));
			});
		});
	});

	Route::group(array('prefix' => '/uam'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/getUAL', array('uses' => 'UAMController@getUAL', 'as' => 'getUAL','middleware' => 'auth'));
			Route::get('/getRoles', array('uses' => 'UAMController@getRoles', 'as' => 'getRoles','middleware' => 'auth'));
			Route::get('/getPermissions', array('uses' => 'UAMController@getPermissions', 'as' => 'getPermissions','middleware' => 'auth'));
		});
	});

	Route::group(array('prefix' => '/filemaintenance'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/getCategory', array('uses' => 'FileMaintenanceController@getCategory', 'as' => 'getCategory','middleware' => 'auth'));
		});
	});

	Route::group(array('prefix' => '/product'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/', array('uses' => 'ProductController@getProductView', 'as' => 'getProductView','middleware' => 'auth'));
			Route::get('/getProductList', array('uses' => 'ProductController@getProductList', 'as' => 'getProductList','middleware' => 'auth'));
			Route::get('/getProductInfo', array('uses' => 'ProductController@getProductInfo', 'as' => 'getProductInfo','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				Route::post('/addProduct', array('uses' => 'ProductController@addProduct', 'as' => 'addProduct','middleware' => 'auth'));
				Route::post('/uploadProductImage', array('uses' => 'ProductController@uploadProductImage', 'as' => 'uploadProductImage','middleware' => 'auth'));
				Route::post('/deleteImage', array('uses' => 'ProductController@deleteImage', 'as' => 'deleteImage','middleware' => 'auth'));
				
			});
		});
	});

});