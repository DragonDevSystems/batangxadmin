<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\Online;
use App\Models\ProductInformation;
use App\Models\ProductSpecs;
use App\Models\ProductImage;
use App\Models\ProCategory;
use App\Models\ProductPrice;
use App\Models\AuditTrail;
use App\Models\ProductInventory;
use Auth;
use DB;
use Input;
use Response;
use Request;

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
						"dm"			=> $userInfo['created_at'],
						"lvl"			=> $username['isAdmin'],
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
		if(($uaccess == 2 || $uaccess == 3) && ($event == "add" || $event == "update" || $event == "update"))
		{
			return Response::json(array(
                'status'  => 'success',
                'message'  => 'Permission granted.',
            ));
		}
		else
		{
			return Response::json(array(
                'status'  => 'fail',
                'message'  => 'You have no permission to add, update, and delete data or records.',
            ));
		}
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

	public function categoryList()
	{
		return ProCategory::all();
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
			return User::where('isVerified','=',1)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function unverifiedUser()
	{
		try 
		{
			return User::where('isVerified','=',0)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function newUser()
	{
		//for current month only
		try 
		{
			return User::where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->get();
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

	public function userlist()
	{
		$response = array();
		$userList = User::where('isAdmin','=',0)->get();//where('id','!=',Auth::User()['id'])->where('isAdmin','=',0)->get();
		//return $userList;
		if(!empty($userList))
		{
			foreach ($userList as $userListi) {
				$response[] = $this->userInfoList($userListi['id']);
			}

			return Response::json(array(
	            'status'  => 'success',
	            'userInfoList'  => $response,
	        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The user record is empty or all user has been listed in the admin account.',
	        ));
		}
	}

	public function topNewProduct($take)
	{
		$response = array();
		$topNewProduct = ProductInformation::take($take)->orderBy('created_at','desc')->get();
		foreach ($topNewProduct as $topNewProducti) {
			$images = ProductImage::where('prod_id','=',$topNewProducti['id'])->orderByRaw("RAND()")->first();
			$proPrice = ProductPrice::where('prod_id','=',$topNewProducti['id'])->where('status','=',1)->first();
			$response[] = array(
				"productInfo" => $topNewProducti,
				"productPrice" => (!empty($proPrice)) ? '&#8369; '.number_format($proPrice['price'], 2) : "Price N/A" ,
				"pro_img" => $images
			);
		}
		return $response;
	}

	public function auditTrail($action,$dataId,$details)
	{
		$ip = Request::ip();

		$insert = new AuditTrail();
		$insert['action'] = $action;
		$insert['data_id'] = $dataId;
		$insert['user_id'] = Auth::User()['id'];
		$insert['details'] = $details;
		$insert['ip_address'] = $ip;
		return (!$insert->save()) ? false : true ;
	}

	public function availabilityCheck($pid)
	{	
		$check = ProductInventory::where("prod_id","=",$pid)->first()['qty'];
		return (!empty($check)) ? $check : 0;
	}
}