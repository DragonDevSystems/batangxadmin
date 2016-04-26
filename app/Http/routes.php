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
Route::get('/category/{category}', array('uses' =>'ProductController@getProByCat', 'as' => 'getProByCat'));
Route::get('/product/{pro_id}/{pro_name}', array('uses' =>'ProductController@productPreview', 'as' => 'productPreview'));
Route::group(array('before' => 'auth'), function()
{
	Route::get('/customer/checkout', array('uses' => 'CustomerController@getCheckOut', 'as' => 'getCheckOut'));
	Route::get('/customer/checkout/print', array('uses' => 'CustomerController@getCheckOutPrint', 'as' => 'getCheckOutPrint'));
});
Route::group(array('prefix' => '/confirmation'),function()
{
	Route::get('/{code}/{id}', array('uses' => 'UserController@confirmation','as' => 'confirmation'));
});
Route::group(array('prefix' => '/contact-us'),function()
{
	Route::get('/', array('uses' =>'HomeController@getContactUs', 'as' => 'getContactUs'));
	Route::group(array('before' => 'csrf'), function()
	{
		Route::post('/postContactUs',array('uses' => 'CustomerController@postContactUs', 'as' => 'postContactUs')); 
	});
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
			Route::post('/user/resetpass',array('uses' => 'UserController@resetPass', 'as' => 'resetPass')); 
			Route::get('/user/getPassReset/{code}/{id}',array('uses' => 'UserController@getPassReset', 'as' => 'getPassReset'));
			Route::post('/user/processResetPass',array('uses' => 'UserController@processResetPass', 'as' => 'processResetPass'));
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
				Route::post('/addAdmin', array('uses' => 'UAMController@addAdmin', 'as' => 'addAdmin','middleware' => 'auth'));
				Route::post('/updateAdmin', array('uses' => 'UAMController@updateAdmin', 'as' => 'updateAdmin','middleware' => 'auth'));
				Route::post('/addToCart', array('uses' => 'ProductController@addToCart', 'as' => 'addToCart','middleware' => 'auth'));
				Route::get('/admin/productOnCart', array('uses' => 'GlobalController@productOnCart', 'as' => 'productOnCart','middleware' => 'auth'));
				Route::get('/admin/onCartList', array('uses' => 'GlobalController@onCartList', 'as' => 'onCartList','middleware' => 'auth'));
				Route::post('/admin/removeOnCart', array('uses' => 'GlobalController@removeOnCart', 'as' => 'removeOnCart','middleware' => 'auth'));
			});
		});
		Route::get('/topNewProduct/{take}', array('uses' => 'GlobalController@topNewProduct', 'as' => 'topNewProduct'));
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
				Route::post('/deleteSpecs', array('uses' => 'ProductController@deleteSpecs', 'as' => 'deleteSpecs','middleware' => 'auth'));
				Route::post('/addPrice', array('uses' => 'ProductController@addPrice', 'as' => 'addPrice','middleware' => 'auth'));
			});
		});
	});
	Route::group(array('prefix' => '/delivery'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/', array('uses' => 'DeliveryController@getDeliveryView', 'as' => 'getDeliveryView','middleware' => 'auth'));
			Route::get('/getReceiptList', array('uses' => 'DeliveryController@getReceiptList', 'as' => 'getReceiptList','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				Route::post('/addDelivery', array('uses' => 'DeliveryController@addDelivery', 'as' => 'addDelivery','middleware' => 'auth'));
				Route::post('/deleteDeliveryProduct', array('uses' => 'DeliveryController@deleteDeliveryProduct', 'as' => 'deleteDeliveryProduct','middleware' => 'auth'));
				Route::post('/addReceipt', array('uses' => 'DeliveryController@addReceipt', 'as' => 'addReceipt','middleware' => 'auth'));
			});
		});
	});
	Route::group(array('prefix' => '/contact-mail'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/', array('uses' => 'ContactMailController@getContactMailView', 'as' => 'getContactMailView','middleware' => 'auth'));
			Route::get('/compose', array('uses' => 'ContactMailController@getComposeMailView', 'as' => 'getComposeMailView','middleware' => 'auth'));
			Route::get('/read-mail/{id}', array('uses' => 'ContactMailController@getReadMailView', 'as' => 'getReadMailView','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				
			});
		});
	});
});