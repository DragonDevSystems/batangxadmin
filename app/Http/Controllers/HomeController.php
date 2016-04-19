<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
use Redirect;
class HomeController extends Controller {

	public function index()
	{
		if(Auth::Check())
		{
			$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
			return View::Make("home.index")->with("userInfo",$userInfo)->with('mt','db');
		}
		else
		{
			return Redirect::route('getLogin');
		}

	}

	public function cusIndex()
	{
		return View::Make("customer.home.index")->with('mt','home');
	}

	public function getAbout()
	{
		return View::Make("customer.menu.about")->with('mt','about');
	}

	public function getNews()
	{
		return View::Make("customer.menu.news")->with('mt','news');
	}

	public function getContactUs()
	{
		return View::Make("customer.menu.contactus")->with('mt','contactus');
	}

}