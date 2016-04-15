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
use File;

class ProductController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getProductView()
	{
		return View::make('product.product')->with("userInfo",$this->userInfo())->with('mt',"pt");
	}

	public function uploadProductImage()
	{
		$images = Input::file('file');
		$processType = Input::get('processType');
		$response = array();
		$x = 0;
		if($processType == "old")
		{
			$oldImage = ProductImage::where('prod_id','=','0')
										->where('uploader_id','=',Auth::User()['id'])
												->get();
			if(!empty($oldImage)){
				foreach ($oldImage as $oldImagei) 
				{
					File::delete(env("FILE_PATH_INTERVENTION").'productImage/' . $oldImagei['img_file']);
					File::delete(env("FILE_PATH_INTERVENTION").'productThumbnail/' . $oldImagei['thumbnail_img']);
					$oldImagei->delete();
				}
			}
		}
		if(!empty($images))
		{
			foreach($images as $image)
			{
				$date = new DateTime();
				$tn_name = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
				$iname = date_format($date, 'U').str_random(110).'.'.$image->getClientOriginalExtension();
				$data = getimagesize($image->getRealPath());
				$newResizing = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],750);
				$move = Image::make($image->getRealPath())->resize($newResizing['width'],$newResizing['height'])->save(env("FILE_PATH_INTERVENTION").'productImage/'.$iname);
				$newResizingTN = App::make('App\Http\Controllers\GlobalController')->imageResized($data[0],$data[1],260);
				$move_tn = Image::make($image->getRealPath())->resize($newResizingTN['width'],$newResizingTN['height'])->save(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$tn_name);
				if($move && $move_tn){

					$addProductImage = new ProductImage();
					$addProductImage['prod_id'] = 0;
					$addProductImage['img_file'] = $iname;
					$addProductImage['thumbnail_img'] = $tn_name;
					$addProductImage['uploader_id'] = Auth::User()['id'];
					if($addProductImage->save()){
						$response[] = array(
							"status" => "success",
							"image_id" => $addProductImage['id'],
							"count" => $x,
						);
					}
				}
				$x++;
			}
		}
		return Response::json($response);
	}

	public function deleteImage()
	{
		$id = Input::get('image');
		$deleteImage = ProductImage::find($id);
		if(!empty($deleteImage)){
			if($deleteImage->delete()){
				return Response::json(array(
						"status" => "success",
						"message" => "Delete success."
					));
			}else{
				return Response::json(array(
						"status" => "fail",
						"message" => "Delete fail."
					));
			}
		}
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