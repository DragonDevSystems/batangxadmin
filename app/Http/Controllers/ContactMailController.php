<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;

class ContactMailController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getContactMailView()
	{
		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml");
	}

}