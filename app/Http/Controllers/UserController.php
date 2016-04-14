<?php 
namespace App\Http\Controllers;

use Validator;
use Input;
use Response;
use Auth;
use Redirect;
use View;
class UserController extends Controller {

	public function getLogin()
	{
		return View('user.login');
	}

	public function postLogin()
	{
		$validator = Validator::make(Input::all(), array(
			'txtUsername' => 'required',
			'txtPassword' => 'required'
		));
		if ($validator -> fails())
		{
			return  Response::json(array(
	                    'status'  => 'fail',
	                    'message'  => 'Fill out all fields.',
	                ));
		}
		else
		{
			$remember = (Input::has('remember')) ? true : false;
			
			$field = filter_var(Input::get('txtUsername'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

			$auth = Auth::attempt(array(
				$field => Input::get('txtUsername'),
				'password' => Input::get('txtPassword'),
				'isAdmin' => true,
			), $remember);

			if($auth)
			{
				return 1;
			}
			else
			{
				return  Response::json(array(
	                    'status'  => 'fail',
	                    'message'  => 'You input invalid credentials. Please Try again.',
	                ));
				//return 2;
			}
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('home');
	}
}