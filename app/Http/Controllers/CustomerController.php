<?php 
namespace App\Http\Controllers;

use App\Models\ProductInformation;
use App\Models\ProductSpecs;
use App\Models\ProductImage;
use App\Models\ProCategory;
use App\Models\ProductPrice;
use App\Models\AuditTrail;
use App\Models\ProductInventory;
use App\Models\ProductOnCart;
use App\Models\ProductReserve;
use App\Models\ProductInvoice;
use App\Models\ProductSold;
use App\Models\UserImage;
use App\Models\User;
use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use Request;
use DateTime;
use App\Models\ContactUs;
use URL;
use Mail;

class CustomerController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function postContactUs()
	{
		$name 		= Input::get('name');
		$email 		= Input::get('email');
		$company 	= Input::get('company');
		$message 	= Input::get('message');

		$add = new ContactUs();
		$add['name'] = $name;
		$add['email'] = $email;
		$add['company'] = $company;
		$add['message'] = $message;
		if($add->save())
		{
			return Redirect::Route('cusIndex')->with('success','Thank you for contacting us.');
		}
		return Redirect::Route('cusIndex')->with('fail','Sorry! Please try again.');

	}
	
	public function getCheckOut()
	{
		$onCartList = App::make("App\Http\Controllers\GlobalController")->onCartList(Auth::User()['id'],1);
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("checkout.index")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db');
	}

	public function getCheckOutPrint($vcode,$id)
	{
		$invoiceCheck = ProductInvoice::where('id','=',$id)->where('vcode','=',$vcode)->first();
		if(!empty($invoiceCheck))
		{
			switch ($invoiceCheck['status']) {
					case 1:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 2:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onSoldList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 3:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 4:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					default:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
				}
			$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList($invoiceCheck['cus_id']);
			$accountNum = str_pad($userInfo['user_id'], 8, '0', STR_PAD_LEFT); //8digit
			$invoiceNum = str_pad($invoiceCheck['id'], 6, '0', STR_PAD_LEFT); //6digit
			$invoiceDate = $invoiceCheck['created_at'];
			$invoiceStatus = App::make("App\Http\Controllers\GlobalController")->invoiceStatus($invoiceCheck['status']);
			return View::Make("checkout.invoiceprint")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db')->with("accountNum",$accountNum)->with("invoiceNum",$invoiceNum)->with("invoiceDate",$invoiceDate)->with("invoiceStatus",$invoiceStatus);
		}
		else
		{
			return "URL is broken or link is already expire.";
		}

	}

	public function invoiceInfoAjax()
	{
		$response = array();
		$inv_id = Input::get('inv_id');
		$invoiceCheck = ProductInvoice::find($inv_id);
		if(!empty($invoiceCheck))
		{
			switch ($invoiceCheck['status']) {
					case 1:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 2:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onSoldList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 3:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					case 4:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
					default:
						$onCartList = App::make("App\Http\Controllers\GlobalController")->onReserveList($invoiceCheck['cus_id'],$invoiceCheck['id']);
						break;
				}
				$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList($invoiceCheck['cus_id']);

				if(empty($onCartList[0]['totalQty'])){
					return Response::json(array(
						"status" => "fail",
						"message" => "No item found for this invoice.",
					));
				}

				$response = array(
					'userInfo' => $userInfo,
					'accountNum' => str_pad($userInfo['user_id'], 8, '0', STR_PAD_LEFT), //8digit
					'invoiceNum' => str_pad($invoiceCheck['id'], 6, '0', STR_PAD_LEFT), //6digit
					'inv_id' => $invoiceCheck['id'],
					'invoiceDate' => $invoiceCheck['created_at'],
					'invoiceStatus' => App::make("App\Http\Controllers\GlobalController")->invoiceStatus($invoiceCheck['status']),
					'onList' => $onCartList,
					"invoicelink" => URL::route('getCheckOutPrint', [$invoiceCheck['vcode'] , $invoiceCheck['id']])
				);
				return Response::json(array(
						"status" => "success",
						"response" => $response,
					));
		}
		else
		{
			return Response::json(array(
					"status" => "fail",
					"message" => "Sorry, system encounter a problem regarding your request. Please try again. Thank you",
				));
		}
	}

	public function cashOnDelivery()
	{
		$check = ProductOnCart::where("cus_id","=",Auth::User()['id'])->where("type","=",1)->get();
		if(!empty($check))
		{
			$date = new DateTime();
			$vCode = date_format($date, 'U').str_random(110);
			$productInovice = new ProductInvoice();
			$productInovice['cus_id'] = Auth::User()['id'];
			$productInovice['remarks'] = "reserved/ Cash on delivery";
			$productInovice['vcode'] = $vCode;
			if(!$productInovice->save())
			{
				return Response::json(array(
					"status" => "fail",
					"message" => "Sorry, system encounter a problem regarding your request. Please try again. Thank you",
				));
			}
			foreach ($check as $checki) {
				$productPrice = ProductPrice::find($checki['price_id']);
				$proreserve = new ProductReserve();
				$proreserve['prod_id'] = $checki['prod_id'];
				$proreserve['cus_id'] = $checki['cus_id'];
				$proreserve['price_id'] = $productPrice['id'];
				$proreserve['prod_invoice_id'] = $productInovice['id'];
				$proreserve['qty'] = $checki['qty'];
				$proreserve['ip_address'] = Request::ip();
				$proreserve->save();
				$remove = ProductOnCart::find($checki['id']);
				$remove->delete();
			}
			$userInfo = User::find(Auth::User()['id']);
			$emailcontent = array (
				'username' => $userInfo->username,
			    'link' => URL::route('getCheckOutPrint', [$vCode , $productInovice ->id])
		    );

			Mail::send('email.reservationConfirmation', $emailcontent, function($message) use ($userInfo)
			{
				$message->to($userInfo['email'],'GameXtreme')->subject('GameXtreme reservation confirmation.');
			});

			return Response::json(array(
					"status" => "success",
					"message" => "Successfully reserved to you account. we will send you an email for your invoice.",
				));
		}
		else
		{
			return Response::json(array(
						"status" => "fail",
						"message" => "No product to be check out.",
					));
		}
	}

	public function walkinCheckOut()
	{
		$cus_id = Input::get("cus_id");
		$type = Input::get("type");
		$check = ProductOnCart::where("cus_id","=",$cus_id )->where("type","=",$type)->get();
		if(empty($check))
		{
			return Response::json(array(
						"status" => "fail",
						"message" => "No product to be check out.",
					));
		}
		$date = new DateTime();
		$vCode = date_format($date, 'U').str_random(110);
		$productInovice = new ProductInvoice();
		$productInovice['cus_id'] = $cus_id;
		$productInovice['remarks'] = "reserved/ Cash on delivery";
		$productInovice['status'] = 2;
		$productInovice['vcode'] = $vCode;
		if(!$productInovice->save())
		{
			return Response::json(array(
				"status" => "fail",
				"message" => "Sorry, system encounter a problem regarding your request. Please try again. Thank you",
			));
		}
		$inv_id = $productInovice['id'];
		foreach ($check as $checki) {
			$productPrice = ProductPrice::find($checki['price_id']);
			$proreserve = new ProductSold();
			$proreserve['prod_id'] = $checki['prod_id'];
			$proreserve['cus_id'] = $checki['cus_id'];
			$proreserve['price_id'] = $productPrice['id'];
			$proreserve['prod_invoice_id'] = $inv_id;
			$proreserve['qty'] = $checki['qty'];
			$proreserve['payment_type'] = 2;// 1: paypall 2: cash
			$proreserve['ip_address'] = Request::ip();
			$proreserve->save();
			$remove = ProductOnCart::find($checki['id']);
			$remove->delete();
		}
		return Response::json(array(
					"status" => "success",
					"message" => "Transaction Successfully process.Thank you for shoping with us.",
					"invoicelink" => URL::route('getCheckOutPrint', [$vCode , $inv_id])
				));
	}
	function getMyaccount()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("customer.user.myaccount")->with("userInfo",$userInfo)->with('mt','db');
	}

	function getInvoiceList($cus_id)
	{
		$cus_id = (!empty($cus_id)) ? $cus_id : Input::get('cus_id');
		$response = array();
		$invoiceList = ProductInvoice::where("cus_id","=",$cus_id)->get();
		if(!empty($invoiceList))
		{
			foreach ($invoiceList as $invoiceListi) {
				$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList($invoiceListi['cus_id']);
				$response[] = array(
						"invoice_num" => str_pad($invoiceListi['id'], 6, '0', STR_PAD_LEFT),
						"invoice_date" => \Carbon\Carbon::createFromTimeStamp(strtotime($invoiceListi['created_at']))->toDayDateTimeString(),
						"cus_name" => $userInfo['fname'].' '.$userInfo['lname'],
						"invoice_link" => URL::route('getCheckOutPrint', [$invoiceListi['vcode'] , $invoiceListi['id']]),
						"status" => App::make("App\Http\Controllers\GlobalController")->invoiceStatus($invoiceListi['status']),
					);
			}
			return Response::json(array(
	            'status'  => 'success',
	            'dataInfo' => $response,
	        ));
		}
		else
		{
			Response::json(array(
	            'status'  => 'fail',
	            'message' => 'No invoice available in your account.',
	        ));
		}
	}

	function getWalkIn()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("product.walkin")->with("userInfo",$userInfo)->with('mt','wi');
	}

	function cancelledReservation($cus_id,$inv_id,$type)
	{
		$cus_id = (!empty($cus_id)) ? $cus_id : Input::get("cus_id");
		$inv_id = (!empty($inv_id)) ? $inv_id : Input::get("inv_id");
		$type = (!empty($type)) ? $type : Input::get("type");
		$check = ProductReserve::where("cus_id","=",$cus_id )->where("prod_invoice_id","=",$inv_id)->get();

		if(empty($check))
		{
			return Response::json(array(
						"status" => "fail",
						"message" => "No product to be cancel in reservation.",
					));
		}

		foreach ($check as $checki) {
			$update = ProductInventory::where("prod_id","=",$checki['prod_id'])->first();
			if(!empty($update))
			{
				$update['qty'] = $update['qty'] + $checki['qty'];
				$update->save();
			}
			$updateRes = ProductReserve::find($checki['id']);
			$updateRes['status'] = $type;
			$updateRes->save();
		}

		$productInovice = ProductInvoice::find($inv_id);
		$productInovice['status'] = $type;
		if(!$productInovice->save())
		{
			return Response::json(array(
				"status" => "fail",
				"message" => "Sorry, system encounter a problem regarding your request. Please try again. Thank you",
			));
		}
		return Response::json(array(
					"status" => "success",
					"message" => "Transaction Successfully cancelled.",
					"invoicelink" => URL::route('getCheckOutPrint', [$productInovice['vCode'] , $inv_id])
				));
	}

	function checkoutReservation()
	{
		$cus_id = (!empty($cus_id)) ? $cus_id : Input::get("cus_id");
		$inv_id = (!empty($inv_id)) ? $inv_id : Input::get("inv_id");
		$check = ProductReserve::where("cus_id","=",$cus_id )->where("prod_invoice_id","=",$inv_id)->get();

		if(empty($check))
		{
			return Response::json(array(
						"status" => "fail",
						"message" => "No product to be cancel in reservation.",
					));
		}
		foreach ($check as $checki) {
			$productPrice = ProductPrice::find($checki['price_id']);
			$proreserve = new ProductSold();
			$proreserve['prod_id'] = $checki['prod_id'];
			$proreserve['cus_id'] = $checki['cus_id'];
			$proreserve['price_id'] = $productPrice['id'];
			$proreserve['prod_invoice_id'] = $checki['prod_invoice_id'];
			$proreserve['qty'] = $checki['qty'];
			$proreserve['payment_type'] = 2;// 1: paypall 2: cash
			$proreserve['ip_address'] = Request::ip();
			$proreserve->save();

			$updateRes = ProductReserve::find($checki['id']);
			$updateRes->delete();
		}

		$productInovice = ProductInvoice::find($inv_id);
		$productInovice['status'] = 2;
		if(!$productInovice->save())
		{
			return Response::json(array(
				"status" => "fail",
				"message" => "Sorry, system encounter a problem regarding your request. Please try again. Thank you",
			));
		}
		return Response::json(array(
					"status" => "success",
					"message" => "Transaction Successfully checkout.",
					"invoicelink" => URL::route('getCheckOutPrint', [$productInovice['vCode'] , $inv_id])
				));
	}
}