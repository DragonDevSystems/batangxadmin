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

	public function postCreate()
	{
		$email = Input::get('email');
		$username = Input::get('username');
		$result1 = User::where('username','=',$username)->first();
		$result2 = Info::where('email','=',$email)->first();

		if(empty($result1) && empty($result2))
		{
			$date = new DateTime();
			$vCode = date_format($date, 'U').str_random(110);		
			$user = new User();
			$user -> username = Input::get('username');
			$user -> password = Hash::make(Input::get('password'));
			$user -> email = $email;
			$user -> Vcode = $vCode;
			
			if ($user -> save())
			{
				//return Redirect::Route('home')->with('success','Your registered successfully. You can now log in.');
				$info = new Info();
				$info -> user_id = $user -> id;
				$info -> email 	= Input::get('email');
				$request = Request::instance();
			    $info-> ip_address = $request->getClientIp(true);

				if ($info -> save())
				{

					$emailcontent = array (
						'username' => $user -> username,
					    'link' => URL::route('confirmation', [$vCode , $user -> id])

				    );
	   				Mail::send('email.index', $emailcontent, function($message)
	    			{ 
					    $message->to(Input::get('email'),'Friendswent')->subject('Friendswent Confirmation Email');
					    
	     			});

	   				$auth = Auth::attempt(array(
						'username' => Input::get('username'),
						'password' => Input::get('password')
					));
	   				if($auth)
	   				{
						//return Redirect::Route('getOtherInfo', [$vCode , $user -> id])->with('success','Please check your email to verify your e-mail.Thank you.');
						return Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'Please check your email to verify your e-mail.Thank you.',
		                    'data' => array(
		                    		"url" => URL::Route('getOtherInfo', [$vCode , $user -> id]),
		                    		"vcode" => $vCode,
		                    		"user_id" => $user -> id,
		                    	),
		                ));
					}
				}
				else
				{
					//return Redirect::Route('home')->with('fail','An error occured while creating the user. Please try again.');
					return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while creating the user. Please try again.',
		                ));
				}
			}
			else
			{
				return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while creating the user. Please try again.',
		                ));
				//return Redirect::Route('home')->with('fail','An error occured while creating the user. Please try again.');
			}
		}
		else
		{
			return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'Your email/username has already taken. Please Try again',
		                ));
		}

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
				'isAdmin' => 3,
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