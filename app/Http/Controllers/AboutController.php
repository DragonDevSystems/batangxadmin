<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use URL;
use DateTime;
use App\Models\Location;
use App\Models\About;
use Image;

class AboutController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getAboutInformation()
	{
		return About::find(1);
	}

	public function getAllLocation()
	{
		return Location::all();
	}

	public function getLocationInfo($id)
	{
		return Location::find($id);
	}

	public function getAboutView()
	{
		$location = Location::all();
		$about = About::find(1);
		return View::make('about.about')->with("userInfo",$this->userInfo())
									->with('location',$location)
									->with('about',$about)
									->with('mt',"ab");
	}

	public function addAddress()
	{
		$address = Input::get('address');
		$add = new Location();
		$add['location'] = $address;
		if($add->save())
		{
			return Response::json(array(
						"status" => "success",
						"message" => "Add success.",
						"id" => $add['id'],
						"location" => $add['location'],
					));
		}
		return Response::json(array(
						"status" => "fail",
						"message" => "Add fail."
					));
	}

	public function deleteAddress()
	{
		$id = Input::get('id');
		$location = Location::find($id);
		if($location->delete())
		{
			return Response::json(array(
						"status" => "success",
						"message" => "Delete success.",
					));
		}
		return Response::json(array(
						"status" => "fail",
						"message" => "Delete fail.",
					));
	}

	public function addAboutInfo()
	{
		$title = Input::get('title');
		$content = Input::get('content');
		$email = Input::get('email');
		$number = Input::get('mobile');
		$fb_link = Input::get('facebook');
		$ebay_link = Input::get('ebay');
		$address = Input::get('address');
		$image = Input::file('image');

		//$banner = About::find(1);
		//$banner['h1'] = $h1;
		//$banner['span'] = $span;
		$about = About::find(1);
		$about['title'] = $title;
		$about['content'] = $content;
		$about['email'] = $email;
		$about['number'] = $number;
		$about['fb_link'] = $fb_link;
		$about['ebay_link'] = $ebay_link;
		$about['address'] = $address;
		if(!empty($image)){
			$date = new DateTime();
			$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
			$data = getimagesize($image->getRealPath());
			$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
			$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'img/'.$iname);
			$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
			$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'img/'.$tn_name);
			$about['img_filename'] = $iname;
			$about['thumbnail'] = $tn_name;
		}
		if($about->save()){
			return Redirect::route('getAboutView')->with('success','Success to update information.');
		}
		else{
			return Redirect::route('getAboutView')->with('fail','Fail to update information.');
		}

	}
}