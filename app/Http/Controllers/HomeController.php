<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
use Redirect;
use App\Models\News;
class HomeController extends Controller {

	public function index()
	{
		if(Auth::Check())
		{
			if(Auth::User()['isAdmin'] != 0)
			{
				$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
				return View::Make("home.index")->with("userInfo",$userInfo)->with('mt','db');
			}
			else
			{
				return $this->cusIndex();
			}
			
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
		$news = News::paginate(3);
		return View::Make("customer.menu.news")->with('mt','news')->with('news',$news);
	}

	public function getContactUs()
	{
		return View::Make("customer.menu.contactus")->with('mt','contactus');
	}

}