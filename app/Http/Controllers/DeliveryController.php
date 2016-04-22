<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;

class DeliveryController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getDeliveryView()
	{
		return View::make('product.delivery')->with("userInfo",$this->userInfo())
									->with('mt',"dl");
	}
	
}