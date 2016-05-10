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
use App\Models\Banner;
use Image;

class BannerController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getBannerView()
	{
		$banner1 = Banner::find(1);
		$banner2 = Banner::find(2);
		$banner3 = Banner::find(3);
		return View::make('banner.banner')->with("userInfo",$this->userInfo())
									->with('banner1',$banner1)
									->with('banner2',$banner2)
									->with('banner3',$banner3)
									->with('mt',"fm")
									->with('cc','bn');
	}

	public function postBanner($id)
	{
		$h1 = Input::get('h1');
		$span = Input::get('span');
		$h2 = Input::get('h2');
		$h4 = Input::get('h4');
		$image = Input::file('image');
		$date = new DateTime();
		$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
		$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
		$data = getimagesize($image->getRealPath());
		$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
		$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'productImage/'.$iname);
		$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
		$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$tn_name);
		
		$banner = Banner::find($id);
		//$banner['h1'] = $h1;
		//$banner['span'] = $span;
		$banner['h2'] = $h2;
		$banner['h4'] = $h4;
		$banner['filename'] = $iname;
		$banner['thumbnail'] = $tn_name;
		if($banner->save()){
			return Redirect::route('getBannerView')->with('success','Success to update banner.');
		}
		else{
			return Redirect::route('getBannerView')->with('fail','Fail to update banner.');
		}
		
	}
}