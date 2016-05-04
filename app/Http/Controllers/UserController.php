<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\PasswordRecovery;
use App\Models\UserImage;
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
use Image;
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
		$validatorEmail = Validator::make(Input::all(), array(
			'email' => 'email'
		));
		if ($validatorEmail -> fails())
		{
			return  Response::json(array(
	                    'status'  => 'fail',
	                    'message'  => 'Email is invalid.',
	                ));
		}
		$validatorPass = Validator::make(Input::all(), array(
			'password' => 'min:6'
		));
		if ($validatorPass -> fails())
		{
			return  Response::json(array(
	                    'status'  => 'fail',
	                    'message'  => 'Your password must be minimum of 6.',
	                ));
		}
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
				$info -> dob 	= date("Y-m-d h:i:sa", strtotime($dob));
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
		                    'message'  => 'Please check your email to verify your e-mail address.Thank you.',
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
	
	public function resendEmailverificationLink()
	{
		$user = User::find(Auth::User()['id']);
		if(empty($user))
		{
			return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'Account not found.',
		                ));
		}

		if($user['isVerified'] != 0)
		{
			return  Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'your account is laready verify.',
		                ));
		}

		if(!empty($user['vCode']))
		{
			$vCode = $user['vCode'];
		}
		else
		{
			$date = new DateTime();
			$vCode = date_format($date, 'U').str_random(110);
			$user['vCode'] = $vCode;
			$user->save();
		}

		$emailcontent = array (
			'username' => $user -> username,
		    'link' => URL::route('confirmation', [$vCode , $user -> id])

	    );
		
		Mail::send('email.regConfirmation', $emailcontent, function($message) use ($user)
		{ 
		    $message->to($user['email'],'GameXtreme')->subject('GameXtreme Re-send Confirmation Email');
		    
		});

		return Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'Please check your email to verify your e-mail address.Thank you.',
		                ));
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
                    'message'  => 'Invalid credentials. Please Try again.',
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
		                    'message'  => 'A mail was sent to your email address to process change of password. Thank you',
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
			return Redirect::Route("cusIndex")->with("fail","You cannot continue this process, maybe your request link is already expired or invalid. Please process a new request to change password.")->with('mt', "db");
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
		                    'message'  => 'Your password has been changed successfully. You may now log in. Thank you.',
		                ));
					}
					else
					{
						return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while resetting your password. Please try again.',
		                ));
					}
				}
				else
				{
					return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occured while resetting your password. Please try again.',
		                ));
				}
			}
		}
		else
		{
			return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'You cannot continue this process, maybe your request link is already expired or invalid. Please process a new request to change password.',
		                ));
		}
	}

	public function uploadProfilePicture()
	{
		$image = Input::file('input_profile_image');
		if(!empty($image))
		{
			$date = new DateTime();
			$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$data = getimagesize($image->getRealPath());
			$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
			$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'userImage/'.$iname);
			$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
			$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'userImage/'.$tn_name);

			if($move_tn && $move)
			{
				$checkDP = UserImage::where('user_id','=',Auth::User()['id'])
											->where('dp','=',1)
												->update(array("dp" => 0));
				$addImage = new UserImage();
				$addImage['img_filename'] = $iname;
				$addImage['img_thumbnail'] = $tn_name;
				$addImage['dp'] = 1;
				$addImage['user_id'] = Auth::User()['id'];
				if($addImage->save())
				{
					return Response::json(array(
		                    'status'  => 'success',
		                    'message'  => 'Upload image successful.',
		                    "image" => 'userImage/'.$tn_name,
		                ));
				}
				return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'Failed to upload image.',
		                ));
			}
		}
		return Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'Failed to upload image.',
		                ));
	}

	public function createClient()
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
		$validatorEmail = Validator::make(Input::all(), array(
			'email' => 'email'
		));
		if ($validatorEmail -> fails())
		{
			return  Response::json(array(
	                    'status'  => 'fail',
	                    'message'  => 'Email Address is invalid.',
	                ));
		}
		$result1 = User::where('username','=',$username)->first();
		$result2 = User::where('email','=',$email)->first();

		if(empty($result1) && empty($result2))
		{
			$date = new DateTime();
			$vCode = date_format($date, 'U').str_random(110);	
			$tempPass = str_random(8);	
			$user = new User();
			$user -> username = Input::get('username');
			$user -> password = Hash::make($tempPass);
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
				$info -> dob 	= date("Y-m-d h:i:sa", strtotime($dob));
				$info -> mobile 	= Input::get('mobile');
				$info -> address 	= Input::get('address');
				$request = Request::instance();
			    $info-> ip_address = $request->getClientIp(true);

				if ($info -> save())
				{

					$emailcontent = array (
						'username' => $user -> username,
					    'link' => URL::route('confirmation', [$vCode , $user -> id]),
					    'tempPass' => $tempPass
				    );
	   				Mail::send('email.walkinRegConfirmation', $emailcontent, function($message)
	    			{ 
					    $message->to(Input::get('email'),'GameXtreme')->subject('GameXtreme Confirmation Email');
					    
	     			});
					return Response::json(array(
	                    'status'  => 'success',
	                    'message'  => 'Client successfully added. Advice the customer to check their email for confirmation. We sent them a temporary password.',
	                ));
				}
				else
				{
					return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occurred while creating the user. Please try again.',
		                ));
				}
			}
			else
			{
				return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'An error occurred while creating the user. Please try again.',
		                ));
			}
		}
		else
		{
			return  Response::json(array(
		                    'status'  => 'fail',
		                    'message'  => 'The email address/username has already been taken. Please Try again',
		                ));
		}
	}

	public function updateUserInfo()
	{

		$username = Input::get('myUsername');
		$fname = Input::get('myFirstname');
		$lname = Input::get('myLastname');
		$gender = Input::get('myGender');
		$dob = Input::get('myDob');
		$email = Input::get('myEmail');
		$mobile = Input::get('myMobile');
		$address = Input::get('myAddress');
		$updateUsername = User::find(Auth::User()['id']);
		$updateUsername['username'] = $username;
		if($updateUsername->save()){
			$updateInfo = Info::where('user_id','=',Auth::User()['id'])->first();
			$updateInfo['first_name'] = $fname;
			$updateInfo['last_name'] = $lname;
			$updateInfo['email'] = $email;
			$updateInfo['mobile'] = $mobile;
			$updateInfo['dob'] =  date("Y-m-d h:i:sa", strtotime($dob));
			$updateInfo['gender'] = $gender;
			$updateInfo['address'] = $address;
			if($updateInfo->save()){
				return Redirect::Route('getMyaccount')->with('success','Update success.');
			}else{
				return Redirect::Route('getMyaccount')->with('fail','Update failed.');
			}
		}
	}
	public function changeUserPass()
	{
		$new_pass = Input::get('myPassword');
		$checkUser = User::find(Auth::User()['id']);
		$checkUser['password'] = Hash::make($new_pass);
		if($checkUser->save()){
			return Redirect::Route('getMyaccount')->with('success','Change password success.');
		}
		return Redirect::Route('getMyaccount')->with('fail','Change password failed.');
	}

	public function checkUserPass()
	{
		$pass = Input::get('pass');
		$checkPass = User::find(Auth::User()['id']);
		/*if($pass == $checkPass['password']){
			return 1;
		}
		else{
			return 2;
		}*/
		$auth = Auth::attempt(array(
				'username' => $checkPass['username'],
				'password' => Input::get('pass'),
			));
		if($auth)
		{
			return Response::json(array(
            	'status'  => 'success',
            	'message'  => 'Credential is correct. ',
        	));
        }
        else{	
        	return Response::json(array(
            	'status'  => 'fail',
            	'message'  => 'Wrong credentials. Please try again.',
        	));
		}
    }
}