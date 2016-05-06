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
use App\Models\ProductInventory;
use App\Models\ProductOnCart;
use Image;
use DateTime;
use File;
use Request;

class ProductController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getProductView()
	{
		$category = ProCategory::where('status','=',1)->get();
		return View::make('product.product')->with("userInfo",$this->userInfo())
								->with('mt',"fm")
									->with('cc',"pt")->with('category',$category);
	}

	public function getInvoiceView()
	{
		return View::make('product.invoice')->with("userInfo",$this->userInfo())
									->with('mt',"inv");
	}

	public function getInventoryView()
	{
		return View::make('product.inventory')->with("userInfo",$this->userInfo())
									->with('mt',"int");
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
		$validate = App::make("App\Http\Controllers\GlobalController")->accountAccessChecker('add','product');
		if($validate['status'] == "fail"){
			return $validate;
		}
		$id = Input::get('id');
		$name = Input::get('name');
		$category = Input::get('category');
		$description = Input::get('description');
		$specs = Input::get('specs');
		$price = Input::get('price');

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
			if(!empty($price))
			{
				/*$cleanPrice = ProductPrice::where('prod_id', $id)
											->where('status', 1)
	            							->update(array('status' => 0));
	            $update = ProductPrice::where('id', $price)
            							->update(array('status' => 1));*/
            	$clean = ProductPrice::where('prod_id','=',0)
            								->update(array('prod_id' => $addProductInfo['id']));
            	$update = ProductPrice::where('id','=',$price)
            							->update(array('status' => 1));
			}

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
			$action = ($id == "new") ? "Add" : "Update";
			App::make("App\Http\Controllers\GlobalController")->auditTrail("product",$addProductInfo['id'],$action." Product ".$addProductInfo['name'].".");
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
			$qty = ProductInventory::where('prod_id','=',$item['id'])->first();

			$response[] = array(
				"id" 			=> $item['id'],
				"name" 			=> $item['name'],
				"description" 	=> $item['description'],
				"featured" 		=> $item['isFeatured'] == 0 ? "fa fa-star-o text-yellow" : "fa fa-star text-yellow",
				"qty" 			=> !empty($qty) ? $qty['qty'] : "No stock.",
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
		$price = ProductPrice::where('prod_id','=',$id)->get();
		$current_price = ProductPrice::where('prod_id','=',$id)->where('status','=',1)->first();
		$remaining_inv = ProductInventory::where('prod_id','=',$id)->first();
		return Response::json(array(
				"status" => !empty($id) ? "Update product" : "Add product",
				"id" => !empty($id) ? $id : "new",
				"name" =>!empty($information) ? $information['name'] : "",
				"description" =>!empty($information) ? $information['description'] : "",
				"pro_cat_id" =>!empty($information) ? $information['pro_cat_id'] : "",
				"images" => !empty($images) ? $images : "",
				"specs" => !empty($specs) ? $specs : "",
				"price" => !empty($price) ? $price : "",
				"current_price" => !empty($current_price) ? $current_price['id'] : "",
				"category" => $category,
				"remaining_inv" => !empty($remaining_inv) ? $remaining_inv['qty'] : 0,
				"current_price_value" => !empty($current_price) ? '&#8369; '.number_format($current_price['price'], 2) : "No Price Available",
				"featured" 	=> $information['isFeatured'] == 0 ? "No" : "Yes",
				));	
	}

	public function deleteSpecs()
	{
		$validate = App::make("App\Http\Controllers\GlobalController")->accountAccessChecker('delete','product');
		if($validate['status'] == "fail"){
			return $validate;
		}
		$id = Input::get('specs');
		$getSpecs = ProductSpecs::find($id);
		$information = ProductInformation::find($getSpecs['prod_id']);
		if(!empty($getSpecs)){
			App::make("App\Http\Controllers\GlobalController")->auditTrail("product",$getSpecs['prod_id'],"Delete specs ".$getSpecs['specs']."for product".$information['name'].".");
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
					if(!empty($proPrice))
					{
						$response[] = array(
							"productInfo" => $productListi,
							"productPrice" => (!empty($proPrice)) ? '&#8369; '.number_format($proPrice['price'], 2) : "Price N/A" ,
							"pro_img" => $images['thumbnail_img']
						);
					}
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
			$qty = App::make("App\Http\Controllers\GlobalController")->availabilityCheck($paramCheck['id']);
			$category = ProCategory::find($paramCheck['pro_cat_id']);
			$remainItem = ProductInventory::where("prod_id","=",$paramCheck['id'])->first();
			$response[] = array(
				"productInfo" => $paramCheck,
				"productPrice" => (!empty($proPrice)) ? '&#8369; '.number_format($proPrice['price'], 2) : "Not specified" ,
				"pro_qty" => (!empty($qty)) ? "Available" : "Out of Stock" ,
				"pro_img" => $images,
				"pro_specs" => $proSpecs,
				"category" => $category['name'],
				"cat_id" => $category['id'],
				"item_remain" => (!empty($remainItem)) ? $remainItem['qty'] : 0,
			);
			return View::Make("customer.product.product_preview")->with('mt','home')->with('response',$response);
		}
		else
		{
			return Redirect::route('cusIndex')->with('fail','Product not found,please try again.');
		}
		
	}

	public function addPrice()
	{
		$price = Input::get('amount');
		$id = Input::get('id');
		$checkNew = Input::get('checkNew');
		if($checkNew == "old"){
			$nProducts = ProductPrice::where('prod_id','=',0)->get();
			if(!empty($nProducts)){
				foreach ($nProducts as $product) {
					$product->delete();
				}
			}
		}
		$add = new ProductPrice();
		$add['prod_id'] = $id;
		$add['price'] = $price;
		$add['status'] = 0;
		if($add->save())
		{
			$information = ProductInformation::find($add['prod_id']);
			App::make("App\Http\Controllers\GlobalController")->auditTrail("product",$add['prod_id'],"Add price ".$add['price']." for product ".$information['name']."");
			$price = ProductPrice::where('prod_id','=',$id)->get();
			$current_price = ProductPrice::where('prod_id','=',$id)->where('status','=',1)->first();
			return Response::json(array(
				"status" => "success",
				"message" => "Success to add price.",
				"price" => !empty($price) ? $price : "",
				"current_price" => !empty($current_price) ? $current_price['id'] : "",
				));	
		}
		return Response::json(array(
				"status" => "fail",
				"message" => "fail to add price."
				));	
	}

	public function addToCart($cus_id)
	{
		if(Auth::check())
		{
			if(Auth::User()['isVerified'] != 1)
			{
				return Response::json(array(
						"status" => "fail",
						"message" => "Sorry you cannot proceed with this trasaction due to unverified email. Go to your mail and check your inbox or spam folder to verify your email. You may use another email for verification, just go to My Account section and click resend email verification.",
					));
			}
			$cus_id = (!empty($cus_id)) ? $cus_id : Input::get('cus_id');
			$prod_id = Input::get('prod_id');
			$qty = Input::get('qty');
			$qtyCheck = App::make("App\Http\Controllers\GlobalController")->availabilityCheck($prod_id);
			$type = Input::get('type');
			if(empty($qtyCheck))
			{
				return Response::json(array(
						"status" => "fail",
						"message" => "Sorry, the product that you are trying to add in your cart is already out of stock.",
					));
			}

			if($qtyCheck < $qty)
			{
				return Response::json(array(
					"status" => "fail",
					"message" => "Sorry, not enough items remaining to complete your request.",
				));
			}

			$update = ProductInventory::where("prod_id","=",$prod_id)->first();
			$update['qty'] = $update['qty'] - $qty;
			if($update->save())
			{
				$current_price = ProductPrice::where('prod_id','=',$prod_id)->where('status','=',1)->first();
				$addCart = new ProductOnCart();
				$addCart['prod_id'] = $prod_id;
				$addCart['cus_id'] = $cus_id;
				$addCart['price_id'] = $current_price['id'];
				$addCart['qty'] = $qty;
				$addCart['type'] = (!empty($type)) ? $type : 1;
				$addCart['ip_address'] = Request::ip();
				$addCart->save();
			}

			return Response::json(array(
				"status" => "success",
				"message" => "The product is succesfully added to your cart.",
			));
		}
		else
		{
			return Response::json(array(
				"status" => "fail",
				"message" => "Please sign in or sign up to continue your shopping.Thank you.",
			));
		}
	}
	public function postFeatured()
	{
		$id = Input::get('id');
		
		//$update['isFeatured'] = $update['isFeatured'] == 0 ? 1 : 0;
		/*if(!empty($update)){
			if($update->save()){
			return Response::json(array(
					"status" => "success",
					"message" => "Success to make it as a featured product.",
					"featured" 	=> $update['isFeatured'] == 0 ? "No" : "Yes",
				));
			}
		}*/
		$update = ProductInformation::find($id);
		$update = ProductInformation::where('id', $id)
            							->update(array('isFeatured' => $update['isFeatured'] == 0 ? 1 : 0));
        //$update = ProductInformation::find($id);
        return Response::json(array(
					"status" => "success",
					"message" => "Update success.",
					"featured" 	=> $update['isFeatured'] == 0 ? "No" : "Yes",
				));
		/*return Response::json(array(
				"status" => "fail",
				"message" => "Fail to make it as a featured product.",
			));*/
	}
}