<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;

class ProductController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getProductView()
	{
		return View::make('product.product')->with("userInfo",$this->userInfo())->with('mt',"pt");
	}
	
}