<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use App\Models\ContactUs;

class CustomerController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function postContactUs()
	{
		$name 		= Input::get('name');
		$email 		= Input::get('email');
		$company 	= Input::get('company');
		$message 	= Input::get('message');

		$add = new ContactUs();
		$add['name'] = $name;
		$add['email'] = $email;
		$add['company'] = $company;
		$add['message'] = $message;
		if($add->save())
		{
			return Redirect::Route('cusIndex')->with('success','Thank you for contacting us.');
		}
		return Redirect::Route('cusIndex')->with('fail','Sorry! Please try again.');

	}
	
	public function getCheckOut()
	{
		$onCartList = App::make("App\Http\Controllers\GlobalController")->onCartList();
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("checkout.index")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db');
	}

	public function getCheckOutPrint()
	{
		$onCartList = App::make("App\Http\Controllers\GlobalController")->onCartList();
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("checkout.invoiceprint")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db');
	}
}