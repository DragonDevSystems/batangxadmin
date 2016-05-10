<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
use Redirect;
use App\Models\News;
use App\Models\Testimonials;
use App\Models\Banner;
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
		$banner1 = Banner::find(1);
		$banner2 = Banner::find(2);
		$banner3 = Banner::find(3);
		return View::Make("customer.home.index")
							->with('banner1',$banner1)
							->with('banner2',$banner2)
							->with('banner3',$banner3)
							->with('mt','home');
	}

	public function termsandcondition()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("user.termsandcondition")->with('mt','home')->with("userInfo",$userInfo);
	}

	public function getAbout()
	{
		//$testimonial = Testimonials::orderBy('created_at', 'DESC')->first();orderByRaw("RAND()")->get();
		$testimonial = Testimonials::orderByRaw("RAND()")->first();
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