<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App\Models\User;
use App;
use Input;
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

	public function getRoles()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("uam.roles")->with("userInfo",$userInfo)->with('mt','uam')->with('cc','roles');
	}

	public function getPermissions()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("uam.permissions")->with("userInfo",$userInfo)->with('mt','uam')->with('cc','perms');
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
		$userAdmins = User::select('id')->lists('id');
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
}