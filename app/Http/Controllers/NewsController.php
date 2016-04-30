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
		$title = Input::get('title');
		$message = Input::get('message');
		$image = Input::file('file');
		$date = new DateTime();
		$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
		$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
		$data = getimagesize($image->getRealPath());
		$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
		$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'productImage/'.$iname);
		$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
		$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$tn_name);
		if($move && $move_tn){

			$addNews = new News();
			$addNews['title'] = $title;
			$addNews['message'] = $message;
			$addNews['img_filename'] = $iname;
			$addNews['img_thumbnail'] = $tn_name;
			if(!$addNews->save()){

			}
			Return Redirect::route('getNewsView');
		}
	}

	public function getNewsList()
	{
		$news =  News::all();
		$data = array();
		if(!empty($news)){
			foreach ($news as $new) {
				$message = str_limit($new['message'], $limit = 60, $end = '...');
				$time = \Carbon\Carbon::createFromTimeStamp(strtotime($new['created_at']))->toDayDateTimeString();
				$data[] = array(
						"id" => $new['id'],
						"title" => $new['title'],
						"message" => $message,
						"time" => $time,
					);
			}
		}
		return Response::json($data);
	}
}