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

Route::get('/', array('uses' =>'HomeController@index', 'as' => 'home'));

Route::group(array('before' => 'guest'), function()
{
	Route::get('/login',array('uses' =>'UserController@getLogin', 'as' => 'getLogin'));
	Route::group(array('before' => 'csrf'), function()
	{
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
			Route::get('/admin/roleList',array('uses' => 'UAMController@rolesList', 'as' => 'rolesList'));
			Route::get('/admin/permissionsList',array('uses' => 'UAMController@permissionsList', 'as' => 'permissionsList'));
			Route::get('/admin/modulesList',array('uses' => 'UAMController@modulesList', 'as' => 'modulesList'));
			Route::get('/admin/uaal', array('uses' => 'UAMController@uaal', 'as' => 'uaal','middleware' => 'auth'));
			Route::get('/admin/rolesInfo', array('uses' => 'UAMController@rolesInfo', 'as' => 'rolesInfo','middleware' => 'auth'));
			Route::get('/admin/permissionInfo', array('uses' => 'UAMController@permissionInfo', 'as' => 'permissionInfo','middleware' => 'auth'));
			Route::get('/admin/information/{id}', array('uses' => 'GlobalController@userInfoList', 'as' => 'userInfoList','middleware' => 'auth'));
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