<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\PasswordRecovery;
use Validator;
use Input;
use Response;
use Auth;
use Redirect;
use View;
use DateTime;
use Request;
use Hash;
use URL;
use Mail;
use App;
class UserController extends Controller {

	public function getLogin()
	{
		return View('user.login');
	}

	public function confirmation($code,$id)
	{
		$account = User::find($id);
		if($account['vCode'] == $code)
		{
			$account['isVerified'] = 1;
			if($account ->save())
			{
				if(Auth::Check())
				{
					return Redirect::Route('cusIndex')->with('success','You already confirmed your email');
				}
				else
				{
					return Redirect::Route('cusIndex')->with('success','You already confirmed your email. Please log in now.');
				}	
			}
			else
			{
				return Redirect::Route('cusIndex')->with('fail','Fail to verify your email. please try again.');
			}
		}
	}

	public function postCreate()
	{
		$email = Input::get('email');
		$username = Input::get('username');
		$password = Input::get('password');
		$fname = Input::get('fname');
		$lname = Input::get('lname');
		$gender = Input::get('gender');
		$dob = Input::get('dob');
		$mobile = Input::get('mobile');
		$address = Input::get('address');

		$validator = Validator::make(Input::all(), array(
			'email' => 'required',
			'username' => 'required',
			'password' => 'required',
			'fname' => 'required',
			'lname' => 'required',
			'gender' => 'required',
			'dob' => 'required',
			'mobile' => 'required',
			'address' => 'required'
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
			$result1 = User::where('username','=',$username)->first();
			$result2 = User::where('email','=',$email)->first();

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
					$info = new Info();
					$info -> user_id = $user -> id;
					$info -> email 	= Input::get('email');
					$info -> first_name 	= Input::get('fname');
					$info -> last_name 	= Input::get('lname');
					$info -> gender 	= Input::get('gender');
					$info -> dob 	= Input::get('dob');
					$info -> mobile 	= Input::get('mobile');
					$info -> address 	= Input::get('address');
					$request = Request::instance();
				    $info-> ip_address = $request->getClientIp(true);

					if ($info -> save())
					{

						$emailcontent = array (
							'username' => $user -> username,
						    'link' => URL::route('confirmation', [$vCode , $user -> id])

					    );
		   				Mail::send('email.regConfirmation', $emailcontent, function($message)
		    			{ 
						    $message->to(Input::get('email'),'GameXtreme')->subject('GameXtreme Confirmation Email');
						    
		     			});

		   				$auth = Auth::attempt(array(
							'username' => Input::get('username'),
							'password' => Input::get('password')
						));
		   				if($auth)
		   				{
							return Response::json(array(
			                    'status'  => 'success',
			                    'message'  => 'Please check your email to verify your e-mail.Thank you.',
			                ));
						}
					}
					else
					{
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
			$source = Input::get('source');
			$remember = (Input::has('remember')) ? true : false;
			
			$field = filter_var(Input::get('txtUsername'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
			$auth = Auth::attempt(array(
				$field => Input::get('txtUsername'),
				'password' => Input::get('txtPassword'),
			), $remember);

			if($auth)
			{
				if($source == "client")
				{
					return 1;
				}
				else
				{
					$adminLvl = ['1','2','3'];
					if(in_array(Auth::User()['isAdmin'],$adminLvl))
					{
						App::make("App\Http\Controllers\GlobalController")->auditTrail("user",Auth::User()['id'],"Login");
						return 1;
					}
					else
					{
						Auth::logout();
					}
				}
				
			}
			return  Response::json(array(
                    'status'  => 'fail',
                    'message'  => 'You input invalid credentials. Please Try again.',
                ));
		}
	}

	public function getLogout()
	{
		App::make("App\Http\Controllers\GlobalController")->auditTrail("user",Auth::User()['id'],"Logout");
		Auth::logout();
		return Redirect::route('cusIndex');
	}

	public function resetPass()
	{
		$email = Input::get('txtUsername');
		$field = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$result = User::where($field,'=',$email)->first();
		if(empty($result))
		{
			return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'No email/username found.',
		                ));
		}
		else
		{
			$date = new DateTime();
			$vCode = date_format($date, 'U').str_random(110);
			$PasswordRecovery = PasswordRecovery::where("user_id","=",$result['id'])->first();
			if(empty($PasswordRecovery))
			{
				$PasswordRecovery = new PasswordRecovery();
				$PasswordRecovery -> user_id = $result['id'];
				$PasswordRecovery -> vcode = $vCode;
			}
			else
			{
				$PasswordRecovery -> vcode = $vCode;
			}
			if($PasswordRecovery->save())
			{
				$emailcontent = array (
					'username' => $result->username,
				    'link' => URL::route('getPassReset', [$vCode , $result ->id])
			    );
				Mail::send('email.forgot', $emailcontent, function($message) use ($result)
				{
					$message->to($result['email'],'GameXtreme')->subject('GameXtreme recovery password Email');
				});

				return Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'Password reset recovery successfully process, please check you email to continue your request.Thank you',
		                ));
			}

		}
	}

	public function getPassReset($code,$id)
	{
		$PasswordRecovery = PasswordRecovery::where("user_id","=",$id)->where("vcode","=",$code)->first();
		if(!empty($PasswordRecovery))
		{
			$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfo($id);
			return View('customer.user.resetPassword')
					->with('mt',"home")
						->with('userInfo',$userInfo)
							->with('code',$code);
		}
		else
		{
			return Redirect::Route("cusIndex")->with("fail","You cannot continue this process, maybe your request link is already expired or invalid link. You ask another request to reset your account password.")->with('mt', "db");
		}

	}

	public function processResetPass()
	{
		$id = Input::get('userid');
		$code = Input::get('vcode');
		$pass = Input::get('pass');
		$PasswordRecovery = PasswordRecovery::where("user_id","=",$id)->where("vcode","=",$code)->first();
		if(!empty($PasswordRecovery))
		{
			$update = User::find($id);
			if(!empty($update))
			{
				$update['password'] = Hash::make($pass);
				if($update->save())
				{
					if($PasswordRecovery->delete())
					{
						return Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'Your password is successfully change. You may log in now.Thank you.',
		                ));
					}
					else
					{
						return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while reseting your password. Please try again.',
		                ));
					}
				}
				else
				{
					return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while reseting your password. Please try again.',
		                ));
				}
			}
		}
		else
		{
			return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'You cannot continue this process, maybe your request link is already expired or invalid link. You ask another request to reset your account password.',
		                ));
		}
	}

}