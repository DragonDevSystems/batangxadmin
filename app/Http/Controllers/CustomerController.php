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
		$onCartList = App::make("App\Http\Controllers\GlobalController")->onCartList();
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("checkout.index")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db');
	}

	public function getCheckOutPrint($vcode,$id)
	{
		$onCartList = App::make("App\Http\Controllers\GlobalController")->onCartList();
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("checkout.invoiceprint")->with("userInfo",$userInfo)->with("onCartList",$onCartList)->with('mt','db');
	}

	public function cashOnDelivery()
	{
		$check = ProductOnCart::where("cus_id","=",Auth::User()['id'])->get();
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
				$productPrice = ProductPrice::where("prod_id","=",$checki['prod_id'])->where("status","=",1)->first();
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
			/*$userInfo = User::find(Auth::User()['id']);
			$emailcontent = array (
				'username' => $userInfo->username,
			    'link' => URL::route('getCheckOutPrint', [$vCode , $productInovice ->id])
		    );

			Mail::send('email.reservationConfirmation', $emailcontent, function($message) use ($userInfo)
			{
				$message->to($userInfo['email'],'GameXtreme')->subject('GameXtreme reservation confirmation.');
			});*/

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
}