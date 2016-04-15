<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use App\Models\ProductInformation;
use App\Models\ProductSpecs;
use App\Models\ProductImage;
use Image;
use DateTime;

class ProductController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getProductView()
	{
		return View::make('product.product')->with("userInfo",$this->userInfo())->with('mt',"pt");
	}

	public function addProduct()
	{
		$name = Input::get('name');
		$category = Input::get('category');
		$description = Input::get('description');
		$specs = Input::get('specs');
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
			$addProductInfo = new ProductInformation();
			$addProductInfo['name'] = $name;
			$addProductInfo['pro_cat_id'] = $category;
			$addProductInfo['description'] = $description;
			if($addProductInfo->save()){

				$addProductImage = new ProductImage();
				$addProductImage['prod_id'] = $addProductInfo['id'];
				$addProductImage['img_file'] = $iname;
				$addProductImage['thumbnail_img'] = $tn_name;
				$addProductImage['status'] = 1;
				if($addProductImage->save()){

					$addProductSpecs = new ProductSpecs();
					$addProductSpecs['prod_id'] = $addProductInfo['id'];
					$addProductSpecs['specs'] = $specs;
					if($addProductSpecs->save()){
						return Response::json(array(
						"status" => "success",
						));
					}
				}
			}
			
		}
		return Response::json(array(
					"status" => "fail",
					));
		
	}
	
}