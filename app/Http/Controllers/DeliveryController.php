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
use App\Models\ProductInventory;

class DeliveryController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getDeliveryView()
	{
		$allProduct = ProductInformation::all();
		return View::make('product.delivery')->with("userInfo",$this->userInfo())
									->with('mt',"fm")
										->with('cc',"dl")
										->with('allProduct',$allProduct);
	}

	public function addDelivery()
	{
		$product = Input::get('product');
		$qty = Input::get('qty');
		$check = Input::get('check');
		//for edit
		$type = Input::get('type');
		$receipt_num = Input::get('receipt_num');
		if($check == "new"){
			$affectedRows = ProductDelivery::where('receipt_num', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->delete();
		}
		if(!empty($product) && !empty($qty)){
			$addDelivery = new ProductDelivery();
			if($type == "edit"){
				$getDelivery = ProductDeliveryReceipt::where('receipt_num','=',$receipt_num)->first();
				$addDelivery['receipt_num'] = $getDelivery['id'];
			}
			else{
				$addDelivery['receipt_num'] = 0;
			}
			
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
		//for edit
		$type = Input::get('type');
		$receipt_num = Input::get('receipt_num');
		if($type ="edit"){
			$getProduct = ProductDelivery::where('prod_id','=',$product)
									->where('receipt_num','=',$receipt_num)
									->where('qty','=',$qty)
									->where('user_id','=',Auth::User()['id'])
									->first();
		}
		else{
			$getProduct = ProductDelivery::where('prod_id','=',$product)
									->where('receipt_num','=',0)
									->where('qty','=',$qty)
									->where('user_id','=',Auth::User()['id'])
									->first();
		}
		
		if(!empty($getProduct)){
			if($getProduct->delete()){
				return Response::json(array(
							"status" => "success",
							"message" => "Delete success.",
						));
			}
		}
		return Response::json(array(
							"status" => "fail",
							"message" => "Delete fail.",
						));
	}

	public function addReceipt()
	{
		$receipt = Input::get('receipt');
		$remarks = Input::get('remarks');
		$addReceipt = new ProductDeliveryReceipt();
		$addReceipt['receipt_num'] = $receipt;
		$addReceipt['remarks'] = $remarks;
		if($addReceipt->save())
		{
            $products = ProductDelivery::where('receipt_num', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->update(array('receipt_num' => $addReceipt['id']));
			$getNewDelivery = ProductDelivery::where('receipt_num','=',$addReceipt['id'])->get();
			if(!empty($getNewDelivery))
			{
				foreach ($getNewDelivery as $getNewDeliveryi) {
					$update = ProductInventory::where("prod_id","=",$getNewDeliveryi['prod_id'])->first();
					if(!empty($update))
					{
						$update['qty'] = $update['qty'] + $getNewDeliveryi['qty'];
						$update->save();
					}
					else
					{
						$add = new ProductInventory();
						$add['prod_id'] = $getNewDeliveryi['prod_id'];
						$add['qty'] = $getNewDeliveryi['qty'];
						$add->save();
					}
				}
			}
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

	public function checkReceiptDelivery()
	{
		$receipt = Input::get('receipt');
		if(!empty($receipt)){
			$checkReceipt = ProductDeliveryReceipt::where('receipt_num','=',$receipt)->first();
			if(!empty($checkReceipt)){
				return Response::json(array(
								"status" => "fail",
								"message" => "Receipt No. is already taken.",
							));
			}
			else{
				return Response::json(array(
								"status" => "success",
								"message" => "Receipt No. is available.",
							));
			}
		}
		else{
			return Response::json(array(
								"status" => "empty",
								"message" => "Delivery Receipt No.",
							));
		}
		
		
	}

	public function getDeliveryProduct()
	{
		$receipt = Input::get('id');
		$response = array();
		$receiptInfo = ProductDeliveryReceipt::find($receipt);
		$getProducts = ProductDelivery::where('receipt_num','=',$receipt)->get();
		if(count($getProducts) > 0)
		{
			foreach ($getProducts as $product) {
				$product_info = ProductInformation::find($product['prod_id']);
				$response[]= array(
						"receipt" => $receiptInfo['receipt_num'],
						"id" => $product_info['id'],
						"name" => $product_info['name'],
						"qty" => $product['qty'],
						"remarks" =>!empty($receiptInfo['remarks']) ? $receiptInfo['remarks'] : "No remarks.",
					);
			}
			return Response::json($response);
		}
		else{
			$response[]= array(
						"receipt" => $receiptInfo['receipt_num'],
					);
			return Response::json($response);
		}
	}
	
}