<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App\Models\User;
use App;
use Input;
use DateTime;
use Response;
use Redirect;
use Image;
use App\Models\News;
use App\Models\Testimonials;

class NewsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getNewsView()
	{
		return View::make('product.news')->with("userInfo",$this->userInfo())
									->with('mt',"nw");
	}

	public function addNews()
	{
		$news_id = Input::get('news_id');
		$title = Input::get('title');
		$message = Input::get('message');
		$image = Input::file('file');
		$date_expired = Input::get('date_expired');
		$date = new DateTime();
		if(!empty($image)){
			$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$data = getimagesize($image->getRealPath());
			$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
			$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'productImage/'.$iname);
			$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
			$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$tn_name);
		}
		
		//if($move && $move_tn){
			if(!empty($news_id)){
				$addNews = News::find($news_id);
				$text = " to update news.";
			}
			else{
				$addNews = new News();
				$text = " to add news.";
			}
			
			$addNews['title'] = $title;
			$addNews['message'] = $message;
			$addNews['date_expired'] = $date_expired;
			if(!empty($image)){
				$addNews['img_filename'] = $iname;
				$addNews['img_thumbnail'] = $tn_name;
			}
			if(!$addNews->save()){
				//return Redirect::Route('getNewsView')->with('fail','Fail');
				return Redirect::Route('getNewsView')->with('fail','fail');
			}
			return Redirect::Route('getNewsView')->with('success','Success');
		//}
	}

	public function getNewsList()
	{
		$news =  News::all();
		$data = array();
		if(!empty($news)){
			foreach ($news as $new) {
				$message = str_limit($new['message'], $limit = 60, $end = '...');
				$time = \Carbon\Carbon::createFromTimeStamp(strtotime($new['created_at']))->toDayDateTimeString();
				$date_expired = \Carbon\Carbon::createFromTimeStamp(strtotime($new['date_expired']))->toDayDateTimeString();
				$data[] = array(
						"id" => $new['id'],
						"title" => $new['title'],
						"message" => $message,
						"time" => $time,
						"date_expired" => $date_expired,
					);
			}
		}
		return Response::json($data);
	}

	public function postTestimonial()
	{
		$message = Input::get('testimonial');
		$add = new Testimonials();
		$add['message'] = $message;
		$add['user_id'] = Auth::User()['id'];
		if($add->save()){
			Return Redirect::route('cusIndex')->with('success','Success to create testimonial.');
		}
		Return Redirect::route('cusIndex')->with('fail','Fail to create testimonial.');
	}

	public function getNewsInfo()
	{
		$news = Input::get('news');
		$checkNews = News::find($news);
		return Response::json(array(
						"id" => !empty($checkNews) ? $checkNews['id'] : "",
						"title" => !empty($checkNews) ? $checkNews['title'] : "",
						"image" => !empty($checkNews) ? $checkNews['img_thumbnail'] : "",
						"message" => !empty($checkNews) ? $checkNews['message'] : "",
						"btn_text" => !empty($checkNews) ? "Update News" : "Save News",
						"required" => !empty($checkNews) ? "" : "required",
					));
	}

	public function deleteNews()
	{
		$news = Input::get('id');
		$deleteNews = News::find($news);
		if($deleteNews->delete()){
			return Response::json(array(
					"status" => "success",
					"message" => "Success to delete news.",
					));
		}
		return Response::json(array(
					"status" => "fail",
					"message" => "Fail to delete news.",
					));
	}
}