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
use App\Models\StockRequest;
use App\Models\ProductRequest;
use App\Models\ProductInformation;
use App\Models\About;
use App\Models\Location;
use App\Models\ProductPrice;
use App\Models\ProductDeliveryReceipt;
use App\Models\ProductInventory;
use Image;
use App\Models\ProductDelivery;
class StockController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function getStockRequestView()
	{
		$allProduct  =  App::make("App\Http\Controllers\GlobalController")->criLvlList();
		return View::make('product.stockRequest')->with("userInfo",$this->userInfo())
									->with('allProduct',$allProduct)
									->with('mt',"str");
	}

	public function addProductRequest()
	{
		$product = Input::get('product');
		$qty = Input::get('qty');
		$check = Input::get('check');
		//for edit
		$type = Input::get('type');
		$receipt_num = Input::get('receipt_num');
		if($check == "new"){
			$affectedRows = ProductRequest::where('request_id', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->delete();
		}
		if(!empty($product) && !empty($qty)){
			$addProductRequest = new ProductRequest();
			if($type == "edit"){
				$getDelivery = StockRequest::where('id','=',$receipt_num)->first();
				$addProductRequest['request_id'] = $getDelivery['id'];
			}
			else{
				$addProductRequest['request_id'] = 0;
			}
			
			$addProductRequest['prod_id'] = $product;
			$addProductRequest['qty'] = $qty;
			$addProductRequest['user_id'] = Auth::User()['id'];
			if($addProductRequest->save())
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

	public function deleteProductRequest()
	{
		$product = Input::get('product');
		$name = Input::get('name');
		$qty = Input::get('qty');
		//for edit
		$type = Input::get('type');
		$receipt_num = Input::get('receipt_num');
		if($type =="edit"){
			$getProduct = ProductRequest::where('prod_id','=',$product)
									->where('request_id','=',$receipt_num)
									->where('qty','=',$qty)
									->where('user_id','=',Auth::User()['id'])
									->first();
		}
		else{
			$getProduct = ProductRequest::where('prod_id','=',$product)
									->where('request_id','=',0)
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

	public function addRequest()
	{
		$receipt = Input::get('receipt');
		$remarks = Input::get('remarks');
		$addReceipt = new StockRequest();
		$addReceipt['request_id'] = $addReceipt['id'];
		if($addReceipt->save())
		{
            $products = ProductRequest::where('request_id', '=', 0)
									->where('user_id', '=', Auth::User()['id'])
										->update(array('request_id' => $addReceipt['id']));
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

	public function getRequestList()
	{
		$StockRequest = StockRequest::orderBy('id','desc')->get();
		$response = array();
		foreach ($StockRequest as $request) {
			$time = \Carbon\Carbon::createFromTimeStamp(strtotime($request['created_at']))->toDayDateTimeString(); 
			$response[] = array(
				"id" => $request['id'],
				"date" => $time,
				"status" => $request['status'] == 0 ? "Pending." : "Received.",
				 );
		}
		return Response::json($response);
	}

	public function getRequestProduct()
	{
		$receipt = Input::get('id');
		$response = array();
		$receiptInfo = StockRequest::find($receipt);
		$getProducts = ProductRequest::where('request_id','=',$receipt)->get();
		if(count($getProducts) > 0)
		{
			foreach ($getProducts as $product) {
				$product_info = ProductInformation::find($product['prod_id']);
				$response[]= array(
						"receipt" => $receiptInfo['id'],
						"id" => $product_info['id'],
						"name" => $product_info['name'],
						"qty" => $product['qty'],
						"remarks" =>$receiptInfo['status'] == 0 ? "Pending" : "Received",
						"url" => URL::Route('getRequestPrint',$receiptInfo['id']),
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

	public function getRequestPrint($id)
	{
		$getProducts = ProductRequest::where('request_id','=',$id)->get();
		$lists = array();
		$request = StockRequest::find($id);
		$date = \Carbon\Carbon::createFromTimeStamp(strtotime($request['created_at']))->toDayDateTimeString(); 
		$totalPrice = 0;
		if(!empty($getProducts)){

			foreach ($getProducts as $product) {
				$product_info = ProductInformation::find($product['prod_id']);
				$price = ProductPrice::where('prod_id','=',$product['prod_id'])->where('status','=',1)->first();
				$total = $product['qty'] * $price['price'];
				$format = number_format($total);
				$lists[]= array(
						"id" => str_pad($product_info['id'], 8, '0', STR_PAD_LEFT),
						"name" => $product_info['name'],
						"qty" => $product['qty'],
						"price" => $format.".00",
					);
				$totalPrice = $totalPrice+ $total;
			}
		}
		$about = About::find(1);
		$location = Location::find($about['address']);
		return View::make('product.stockRequestPrint')->with("userInfo",$this->userInfo())
									->with('totalPrice',number_format($totalPrice).".00")
									->with('request',$request)
									->with('location',$location)
									->with('about',$about)
									->with('lists',$lists)
									->with('date',$date)
									->with('mt',"str");
	}

	public function requestDelivered()
	{
		$stock_request = Input::get('stock_request');
		$receipt = Input::get('receipt');
		$remarks = Input::get('remarks');
		$updateRequest = StockRequest::find($stock_request);
		$updateRequest['status'] = 1;
		if($updateRequest->save())
		{
			$addReceipt = new ProductDeliveryReceipt();
			$addReceipt['receipt_num'] = $receipt;
			$addReceipt['remarks'] = $remarks;
			if($addReceipt->save())
			{
				$getNewDelivery = ProductRequest::where('request_id','=',$stock_request)->get();
				if(!empty($getNewDelivery))
				{
					foreach ($getNewDelivery as $getNewDeliveryi) {
						$saveProduct = new ProductDelivery();
						$saveProduct['prod_id'] = $getNewDeliveryi['prod_id'];
						$saveProduct['receipt_num'] = $addReceipt['id'];
						$saveProduct['qty'] = $getNewDeliveryi['qty'];
						$saveProduct['user_id'] = Auth::User()['id'];
						$saveProduct->save();
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
								"link"=> URL::Route('getDeliveryView'),
							));
			}
			return Response::json(array(
							"status" => "fail",
							"message" => "Add fail.",
						));
		}
		return Response::json(array(
							"status" => "fail",
							"message" => "Add fail.",
						));
	}
}