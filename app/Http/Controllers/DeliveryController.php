<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use App\Models\ProductInformation;
use App\Models\ProductDelivery;
use App\Models\ProductDeliveryReceipt;

class DeliveryController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getDeliveryView()
	{
		$allProduct = ProductInformation::all();
		return View::make('product.delivery')->with("userInfo",$this->userInfo())
									->with('mt',"dl")
										->with('allProduct',$allProduct);
	}

	public function addDelivery()
	{
		$product = Input::get('product');
		$qty = Input::get('qty');
		$check = Input::get('check');
		if($check == "new"){
			$affectedRows = ProductDelivery::where('receipt_num', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->delete();
		}
		if(!empty($product) && !empty($qty)){
			$addDelivery = new ProductDelivery();
			$addDelivery['receipt_num'] = 0;
			$addDelivery['prod_id'] = $product;
			$addDelivery['qty'] = $qty;
			$addDelivery['user_id'] = Auth::User()['id'];
			if($addDelivery->save())
			{
				return Response::json(array(
							"status" => "success",
							"message" => "Add success.",
						));
			}
		}
		
		return Response::json(array(
						"status" => "fail",
						"message" => "Add fail.",
					));
	}

	public function deleteDeliveryProduct()
	{
		$product = Input::get('product');
		$name = Input::get('name');
		$qty = Input::get('qty');

		$getProduct = ProductDelivery::where('prod_id','=',$product)
									->where('receipt_num','=',0)
									->where('qty','=',$qty)
									->where('user_id','=',Auth::User()['id'])
									->first();
		if(!empty($getProduct)){
			if($getProduct->delete()){
				return Response::json(array(
							"status" => "success",
							"message" => "Delete success.",
						));
			}
		}
		return Response::json(array(
							"status" => "success",
							"message" => "Delete fail.",
						));
	}

	public function addReceipt()
	{
		$receipt = Input::get('receipt');
		$addReceipt = new ProductDeliveryReceipt();
		$addReceipt['receipt_num'] = $receipt;
		if($addReceipt->save())
		{
            $products = ProductDelivery::where('receipt_num', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->update(array('receipt_num' => $addReceipt['id']));
			return Response::json(array(
							"status" => "success",
							"message" => "Add success.",
						));
		}
		return Response::json(array(
							"status" => "fail",
							"message" => "Add fail.",
						));
	}

	public function getReceiptList()
	{
		return ProductDeliveryReceipt::all();
	}
	
}