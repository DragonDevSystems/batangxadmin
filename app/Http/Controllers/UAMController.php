<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App\Models\User;
use App;
use Input;
use Response;

class UAMController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getUAL()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("uam.ual")->with("userInfo",$userInfo)->with('mt','uam')->with('cc','ual');
	}

	//userAdminAccessLvl
	public function uaal() 
	{
		$response = array();
		$id = Input::get("uid");
		if(!empty($id))
		{
			$response = array(
				"uinfo" => App::make("App\Http\Controllers\GlobalController")->userInfoList($id),
			);
		}
		return $response;
	}
	
	public function adminUserList()
	{
		$response = array();
		$userAdmins = User::where("isAdmin","!=",0)->select('id')->lists('id');
		if(!empty($userAdmins))
		{
			foreach ($userAdmins as $userAdmin) {
				$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList($userAdmin);
				if(!empty($userInfo))
				{
					$response[] = $userInfo;
				}
			}
		}
		return $response;
	}

	public function addAdmin()
	{
		$uid = Input::get('uid');
		$role = Input::get('role');
		$userCheck = User::where("id","=",$uid)->where("isAdmin","=",0)->first();
		if(!empty($userCheck))
		{
			$userCheck['isAdmin'] = $role;
			if($userCheck->save())
			{
				return Response::json(array(
		            'status'  => 'success',
		            'message'  => 'You successfully added new admin.',
		        ));
			}
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'Fail to add new admin. System has encounter unnecessary error. Please Try again.',
	        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The user that are you trying to add is already in the admin. You can search it on the table below and update there access.',
	        ));
		}
	}

	public function updateAdmin()
	{
		$uid = Input::get('uid');
		$role = Input::get('role');
		$roleChecker = User::where("id","=",$uid)->where("isAdmin","!=",0)->first();
		if(!empty($roleChecker))
		{
			$roleChecker['isAdmin'] = $role;
			if($roleChecker->save())
			{
				return Response::json(array(
		            'status'  => 'success',
		            'message'  => 'You successfully update the particular admin.',
		        ));
			}
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'Fail to update admin user. System has encounter unnecessary error. Please Try again.',
	        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The user that are you trying to update is not yet in the admin record. Please check and try again.',
	        ));
		}
	}
}