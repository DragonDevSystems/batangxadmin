<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\Online;
use Auth;
use DB;
use Input;
use Response;

class GlobalController extends Controller {

	public function userInfo($id)
	{
		return Info::where('user_id','=',$id)->first();
	}

	public function userInfoList($uid)
	{
		$id = (!empty($uid)) ? $uid : Input::get("uid");
		$userInfo = $this->userInfo($id);
		$username = User::find($id);
		if(!empty($userInfo))
		{
			return array(
						"user_id" 		=> $userInfo['user_id'],
						"un"			=> $username['username'],
						"userDp"		=> $this->userDpv2($userInfo['user_id']),
						"fname"			=> $userInfo['first_name'],
						"lname"			=> $userInfo['last_name'],
					);
		}
	}

	public function userDP($id)
	{
		return User::find($id)['userImages'];
	}

	public function userDpv2($id)
	{
		/*$userInfo = $this->userInfo($id);
		$userDPs = $this->userDP($id);
		$avatar = false;

		if(count($userDPs) != 0)
		{
			foreach($userDPs as $userDP)
			{
				if($userDP['fw_dp'] == 1)
				{
					$userDPics = $userDP['fw_img_thumbnail'];
					$avatar = true;
				}
			}
		}

		if(!$avatar)
		{
			if($userInfo['fw_gender'] == 1)
			{
				$userDPics = "maleimgplaceholder.jpg";
			}
			else
			{
				$userDPics = "femaleimgplaceholder.jpg";
			}
		}*/

		return "img/person1.png";//.$userDPics;
	}

	public function statsbox()
	{
		return array (
						"nu" 	=> count($this->newUser()),//new user this month
						"ou"	=> count($this->onlineUser()),// online user near realtime
						"ru"	=> count($this->registeredUser()),// total of registered user
						"uvu"	=> count($this->unverifiedUser()),//un-verified user
					);

	}

	public function onlineUser()
	{
		try 
		{
			Online::updateCurrent();
			return Online::whereNotNull('user_id')->select('user_id')->groupBy('user_id')->get()->toArray();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function registeredUser()
	{
		try 
		{
			return User::where('isOtherVerify','=',1)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function unverifiedUser()
	{
		try 
		{
			return User::where('isOtherVerify','=',0)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function newUser()
	{
		//for current month only
		try 
		{
			return User::where('MONTH(created_at)','=',date('m'))->where('YEAR(created_at)','=',date('Y'))->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function allCountry()
	{
		return Country::all();
	}

	public function countryInfo($id)
	{
		return Country::find($id);
	}

	public function cityInfo($id)
	{
		return DB::table("fm_city")->whereid($id)->first();
	}

	/*
	|--------------------------------------------------------------------------
	| checkinFeeds
	|--------------------------------------------------------------------------
	|
	| Get the lat and lng of maps coordinate with information for news feeds with map display.\
	| Parammeter:
	| 	$id = Individual feeds id.
	|
	*/
	public function checkinFeeds($id)
	{
		return 	Places::find($id);
	}

	public function userPermissionCheck($slug)
	{
		$roleUser = RoleUser::where('user_id','=',Auth::User()['id'])->first()['user_id'];
		if(!empty($roleUser))
		{
			$role = Role::find($roleUser);
			if ($role->can($slug))
			{
				return  array(
	                    'status'  => 'success',
	                    'message'  => 'You input invalid credentials. Please Try again.',
	                );
			}
			else
			{
				return  array(
	                    'status'  => 'fail',
	                    'message'  => 'You have no permission for this modules',
	                );
			}
		}
	}

	//resize image to default max width or high
	public function imageResized($width,$height,$size)
	{
		if($width <= $size && $height <= $size)
		{
			return array(
					"width"	=> $width,
					"height" => $height,
				);
		}
		else
		{
			$percentagetoLess = ($width > $height) ? ((($width - $size)/$width)*100) : ((($height - $size)/$height)*100);
			return array(
					"width"	=> intval($width * ((100-$percentagetoLess) / 100)),
					"height" => intval($height * ((100-$percentagetoLess) / 100)),
				);
		}
	}
}