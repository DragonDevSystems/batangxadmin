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
	Route::get('/customer/myaccount', array('uses' => 'CustomerController@getMyaccount', 'as' => 'getMyaccount'));
	Route::get('/customer/getInvoiceList/{id}', array('uses' => 'CustomerController@getInvoiceList', 'as' => 'getInvoiceList'));
	Route::get('/customer/checkout/print/{vcode}/{id}', array('uses' => 'CustomerController@getCheckOutPrint', 'as' => 'getCheckOutPrint'));
	Route::get('/customer/checkout/paypal', array('uses' => 'PaypalController@getCheckout', 'as' => 'getCheckout'));
	Route::post('/customer/getDone/paypal', array('uses' => 'PaypalController@getDone', 'as' => 'getDone'));
	Route::get('/customer/getCancel/paypal', array('uses' => 'PaypalController@getCancel', 'as' => 'getCancel'));
	Route::post('/customer/checkout/cop', array('uses' => 'CustomerController@cashOnDelivery', 'as' => 'cashOnDelivery'));
	Route::post('/customer/updateUserInfo', array('uses' => 'UserController@updateUserInfo', 'as' => 'updateUserInfo'));
	Route::post('/customer/changeUserPass', array('uses' => 'UserController@changeUserPass', 'as' => 'changeUserPass'));
	Route::get('/customer/checkUserPass', array('uses' => 'UserController@checkUserPass', 'as' => 'checkUserPass'));

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
			Route::post('/user/createClient', array('uses' => 'UserController@createClient', 'as' => 'createClient'));
			Route::post('/user/login',array('uses' => 'UserController@postLogin', 'as' => 'postLogin')); 
			Route::post('/user/resetpass',array('uses' => 'UserController@resetPass', 'as' => 'resetPass')); 
			Route::get('/user/getPassReset/{code}/{id}',array('uses' => 'UserController@getPassReset', 'as' => 'getPassReset'));
			Route::post('/user/processResetPass',array('uses' => 'UserController@processResetPass', 'as' => 'processResetPass'));
			Route::post('/user/uploadProfilePicture',array('uses' => 'UserController@uploadProfilePicture', 'as' => 'uploadProfilePicture'));
			
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
				Route::get('/admin/cuslist', array('uses' => 'GlobalController@cuslist', 'as' => 'cuslist','
					middleware' => 'auth'));
				Route::get('/admin/allProduct', array('uses' => 'GlobalController@allProduct', 'as' => 'allProduct','
					middleware' => 'auth'));
				Route::get('/admin/accountAccessChecker/{event}/{module}', array('uses' => 'GlobalController@accountAccessChecker', 'as' => 'accountAccessChecker','
					middleware' => 'auth'));
				Route::get('/product/categoryList', array('uses' => 'FileMaintenanceController@categoryList', 'as' => 'categoryList','middleware' => 'auth'));
				Route::get('/product/categoryInfo/{cid}', array('uses' => 'GlobalController@categoryInfo', 'as' => 'categoryInfo','middleware' => 'auth'));

				Route::post('/product/addCategory', array('uses' => 'FileMaintenanceController@addCategory', 'as' => 'addCategory','middleware' => 'auth'));
				Route::post('/addAdmin', array('uses' => 'UAMController@addAdmin', 'as' => 'addAdmin','middleware' => 'auth'));
				Route::post('/updateAdmin', array('uses' => 'UAMController@updateAdmin', 'as' => 'updateAdmin','middleware' => 'auth'));
				Route::post('/addToCart/{id}', array('uses' => 'ProductController@addToCart', 'as' => 'addToCart'));
				Route::get('/admin/productOnCart', array('uses' => 'GlobalController@productOnCart', 'as' => 'productOnCart'));
				Route::get('/admin/onCartList/{id}/{type}', array('uses' => 'GlobalController@onCartList', 'as' => 'onCartList'));
				Route::post('/admin/removeOnCart', array('uses' => 'GlobalController@removeOnCart', 'as' => 'removeOnCart','middleware' => 'auth'));
				Route::get('/admin/stats/{entry}', array('uses' => 'GlobalController@statsList', 'as' => 'statsList','middleware' => 'auth'));
				Route::get('/admin/statsSummary/{entry}', array('uses' => 'GlobalController@statsSummary', 'as' => 'statsSummary','middleware' => 'auth'));
				Route::get('/admin/invoiceList/', array('uses' => 'GlobalController@invoiceList', 'as' => 'invoiceList','middleware' => 'auth'));
				Route::get('/admin/invoiceInfoAjax/', array('uses' => 'CustomerController@invoiceInfoAjax', 'as' => 'invoiceInfoAjax','middleware' => 'auth'));
				Route::post('/admin/checkoutReservation/', array('uses' => 'CustomerController@checkoutReservation', 'as' => 'checkoutReservation','middleware' => 'auth'));
				Route::post('/admin/cancelledReservation/{cus_id}/{inv_id}/{type}', array('uses' => 'CustomerController@cancelledReservation', 'as' => 'cancelledReservation','middleware' => 'auth'));
			});
		});
		Route::get('/topNewProduct/{take}', array('uses' => 'GlobalController@topNewProduct', 'as' => 'topNewProduct'));
	});

	Route::group(array('prefix' => '/uam'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/getTransactionHistory', array('uses' => 'UAMController@getTransactionHistory', 'as' => 'getTransactionHistory','middleware' => 'auth'));
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
			Route::get('/getInvoiceView', array('uses' => 'ProductController@getInvoiceView', 'as' => 'getInvoiceView','middleware' => 'auth'));
			Route::get('/getWalkIn', array('uses' => 'CustomerController@getWalkIn', 'as' => 'getWalkIn','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				Route::post('/addProduct', array('uses' => 'ProductController@addProduct', 'as' => 'addProduct','middleware' => 'auth'));
				Route::post('/uploadProductImage', array('uses' => 'ProductController@uploadProductImage', 'as' => 'uploadProductImage','middleware' => 'auth'));
				Route::post('/deleteImage', array('uses' => 'ProductController@deleteImage', 'as' => 'deleteImage','middleware' => 'auth'));
				Route::post('/deleteSpecs', array('uses' => 'ProductController@deleteSpecs', 'as' => 'deleteSpecs','middleware' => 'auth'));
				Route::post('/addPrice', array('uses' => 'ProductController@addPrice', 'as' => 'addPrice','middleware' => 'auth'));
				Route::post('/postFeatured', array('uses' => 'ProductController@postFeatured', 'as' => 'postFeatured','middleware' => 'auth'));
				Route::post('/walkinCheckOut', array('uses' => 'CustomerController@walkinCheckOut', 'as' => 'walkinCheckOut','middleware' => 'auth'));
			});
		});
	});
	Route::group(array('prefix' => '/delivery'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/', array('uses' => 'DeliveryController@getDeliveryView', 'as' => 'getDeliveryView','middleware' => 'auth'));
			Route::get('/getReceiptList', array('uses' => 'DeliveryController@getReceiptList', 'as' => 'getReceiptList','middleware' => 'auth'));
			Route::get('/checkReceiptDelivery', array('uses' => 'DeliveryController@checkReceiptDelivery', 'as' => 'checkReceiptDelivery','middleware' => 'auth'));
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
			Route::get('/trash-mail', array('uses' => 'ContactMailController@getTrashMailView', 'as' => 'getTrashMailView','middleware' => 'auth'));
			Route::get('/compose', array('uses' => 'ContactMailController@getComposeMailView', 'as' => 'getComposeMailView','middleware' => 'auth'));
			Route::get('/read-mail/{type}/{id}', array('uses' => 'ContactMailController@getReadMailView', 'as' => 'getReadMailView','middleware' => 'auth'));
			Route::get('/getNextMail', array('uses' => 'ContactMailController@getNextMail', 'as' => 'getNextMail','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				Route::post('/moveToTrash', array('uses' => 'ContactMailController@moveToTrash', 'as' => 'moveToTrash','middleware' => 'auth'));
				Route::post('/deleteMail', array('uses' => 'ContactMailController@deleteMail', 'as' => 'deleteMail','middleware' => 'auth'));
			});
		});
	});

	Route::group(array('prefix' => '/news'),function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/', array('uses' => 'NewsController@getNewsView', 'as' => 'getNewsView','middleware' => 'auth'));
			Route::get('/getNewsList', array('uses' => 'NewsController@getNewsList', 'as' => 'getNewsList','middleware' => 'auth'));
			Route::group(array('before' => 'csrf'), function()
			{
				Route::post('/addNews', array('uses' => 'NewsController@addNews', 'as' => 'addNews','middleware' => 'auth'));
				Route::post('/create_testimonial', array('uses' => 'NewsController@postTestimonial', 'as' => 'postTestimonial','middleware' => 'auth'));
			});
		});

	});
});