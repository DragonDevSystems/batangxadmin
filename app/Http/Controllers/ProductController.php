<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use App\Models\ProductInformation;
use App\Models\ProductSpecs;
use App\Models\ProductImage;
use App\Models\ProCategory;
use App\Models\ProductPrice;
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
		$category = ProCategory::where('status','=',1)->get();
		return View::make('product.product')->with("userInfo",$this->userInfo())
									->with('mt',"pt")->with('category',$category);
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
					File::delete(env("FILE_PATH_INTERVENTION").'productImage/'.$oldImagei['img_file']);
					File::delete(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$oldImagei['thumbnail_img']);
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
			File::delete(env("FILE_PATH_INTERVENTION").'productImage/'.$deleteImage['img_file']);
			File::delete(env("FILE_PATH_INTERVENTION").'productThumbnail/'.$deleteImage['thumbnail_img']);
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
		$id = Input::get('id');
		$name = Input::get('name');
		$category = Input::get('category');
		$description = Input::get('description');
		$specs = Input::get('specs');

		if($id == "new"){
			$addProductInfo = new ProductInformation();
		}
		else{
			$addProductInfo = ProductInformation::find($id);
		}
		$addProductInfo['name'] = $name;
		$addProductInfo['pro_cat_id'] = $category;
		$addProductInfo['description'] = $description;
		if($addProductInfo->save()){
			$image = ProductImage::where('prod_id', '=', 0)
							->where('uploader_id', '=', Auth::User()['id'])
								->update(array(
									'prod_id' => $addProductInfo['id'],
									'status' => 1,
									));
			if(!empty($specs)){
				foreach ($specs as $specsi) {
					$addProductSpecs = new ProductSpecs();
					$addProductSpecs['prod_id'] = $addProductInfo['id'];
					$addProductSpecs['specs'] = $specsi;
					if(!$addProductSpecs->save()){
						return Response::json(array(
						"status" => "fail",
						"message" => ($id == "new") ? "Failed to add product." : "Failed to update product.",
						));	
					}
				}
			}
			
			return Response::json(array(
					"status" => "success",
					"message" => ($id == "new") ? "Success to add product." : "Success to update product.",
					));
			
		}

		return Response::json(array(
					"status" => "fail",
					"message" => ($id == "new") ? "Failed to add product." : "Failed to update product.",
					));	
	}

	public function getProductList()
	{
		$lists = ProductInformation::all();
		$response = array();
		foreach ($lists as $item) {
			$response[] = array(
				"id" 			=> $item['id'],
				"name" 			=> $item['name'],
				"description" 	=> $item['description'],
				);
		}
		return Response::json($response);	
	}

	public function getProductInfo()
	{
		$id = Input::get('product');
		$information = ProductInformation::find($id);
		$images = ProductImage::where('prod_id','=',$id)->get(array('thumbnail_img','id'));
		$specs = ProductSpecs::where('prod_id','=',$id)->get(array('specs','id'));
		$category = ProCategory::where('status','=',1)->get(array('id','name'));
		return Response::json(array(
				"status" => !empty($id) ? "Update product" : "Add product",
				"id" => !empty($id) ? $id : "new",
				"name" =>!empty($information) ? $information['name'] : "",
				"description" =>!empty($information) ? $information['description'] : "",
				"pro_cat_id" =>!empty($information) ? $information['pro_cat_id'] : "",
				"images" => !empty($images) ? $images : "",
				"specs" => !empty($specs) ? $specs : "",
				"category" => $category,
				));	
	}

	public function deleteSpecs()
	{
		$id = Input::get('specs');
		$getSpecs = ProductSpecs::find($id);
		if(!empty($getSpecs)){
			if($getSpecs->delete()){
				return Response::json(array(
					"status" => "success",
					"message" => "Success to delete previous specs.",
					));	
			}
			return Response::json(array(
					"status" => "fail",
					"message" => "Failed to delete previous specs.",
					));
		}
		return Response::json(array(
					"status" => "fail",
					"message" => "Failed to delete previous specs.",
					));
	}
	
	public function getProByCat($category)
	{
		$response = array();
		$catInfo = ProCategory::where("name","=",$category)->first();
		if(!empty($catInfo))
		{
			$productList = ProductInformation::where("pro_cat_id","=",$catInfo['id'])->get();
			//return $productList;
			if(!empty($productList))
			{
				foreach ($productList as $productListi) {
					$images = ProductImage::where('prod_id','=',$productListi['id'])->first();
					$proPrice = ProductPrice::where('prod_id','=',$productListi['id'])->where('status','=',1)->first();
					$response[] = array(
						"productInfo" => $productListi,
						"productPrice" => (!empty($proPrice)) ? '&#8369; '.$proPrice['price'] : "Price N/A" ,
						"pro_img" => $images['thumbnail_img']
					);
				}
			}
		}
		return View::Make("customer.product.product_by_cat")->with('mt','home')->with('response',$response);
	}

	public function productPreview($id,$name)
	{
		$response = array();
		$paramCheck = ProductInformation::where("id","=",$id)->where("name","=",$name)->first();
		if(!empty($paramCheck))
		{
			$images = ProductImage::where('prod_id','=',$paramCheck['id'])->get();
			$proSpecs = ProductSpecs::where('prod_id','=',$paramCheck['id'])->get();
			$proPrice = ProductPrice::where('prod_id','=',$paramCheck['id'])->where('status','=',1)->first();
			$response[] = array(
				"productInfo" => $paramCheck,
				"productPrice" => (!empty($proPrice)) ? '&#8369; '.$proPrice['price'] : "Not specified" ,
				"pro_img" => $images,
				"pro_specs" => $proSpecs
			);
			return View::Make("customer.product.product_preview")->with('mt','home')->with('response',$response);
		}
		else
		{
			return Redirect::route('cusIndex')->with('fail','Product not found,please try again.');
		}
		
	}
}