<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
use Redirect;
use App\Models\News;
use App\Models\Testimonials;
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
		$testimonial = Testimonials::orderBy('created_at', 'DESC')->first();
		$testiInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList($testimonial['user_id']);
		return View::Make("customer.menu.about")->with('mt','about')->with('testimonial',$testimonial)->with('testiInfo',$testiInfo);
	}

	public function getNews()
	{

		$news = News::orderBy('created_at', 'DESC')->paginate(3);
		return View::Make("customer.menu.news")->with('mt','news')->with('news',$news);
	}

	public function getContactUs()
	{
		return View::Make("customer.menu.contactus")->with('mt','contactus');
	}

}