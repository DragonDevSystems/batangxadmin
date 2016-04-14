<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\Online;
use App\Models\ProCategory;
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

	public function accountAccessChecker($event)
	{
		$event = (!empty($event)) ? $event : Input::get('event');
		$uaccess = Auth::User()['isAdmin'];
		return $uaccess;
	}

	public function userDpv2($id)
	{
		return "img/person1.png";//.$userDPics;
	}

	public function statsbox()
	{
		return array(
						"nu" 	=> count($this->newUser()),//new user this month
						"ou"	=> count($this->onlineUser()),// online user near realtime
						"ru"	=> count($this->registeredUser()),// total of registered user
						"uvu"	=> count($this->unverifiedUser()),//un-verified user
					);

	}

	public function categoryInfo($cid)
	{
		$id = (!empty($cid)) ? $cid : Input::get("cid");
		return ProCategory::find($id);
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