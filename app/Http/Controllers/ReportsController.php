<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App\Models\User;
use App;
use Input;
use Response;
use URL;
use DB;
use App\Models\ProductInformation;
use App\Models\ProductSpecs;
use App\Models\ProductImage;
use App\Models\ProCategory;
use App\Models\ProductPrice;
use App\Models\AuditTrail;
use App\Models\ProductInventory;
use App\Models\ProductOnCart;
use App\Models\UserImage;
use App\Models\ProductReserve;
use App\Models\ContactUs;
use App\Models\ProductInvoice;
use App\Models\ProductSold;

class ReportsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getSales()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("reports.sales")->with("userInfo",$userInfo)->with('mt','sr');
	}

	public function printSales($sdate,$edate)
	{
		$byDate = array();
		$response = array();
		$products = array();
		$allTotal = 0;
		$total = ProductSold::where(DB::raw('DATE(created_at)'),'>=',$sdate)->where(DB::raw('DATE(created_at)'),'<=',$edate)->get();

		if(!empty($total))
		{
			foreach ($total as $totali) {
				if(!in_array(date('Y-m-d',strtotime($totali['created_at'])),$byDate))
				{
					$byDate[] = date('Y-m-d',strtotime($totali['created_at']));
				}
				$prodInfo = ProductInformation::find($totali['prod_id']);
				$current_price = ProductPrice::find($totali['price_id']);
				$products[] = array(
						"date" => date('Y-m-d',strtotime($totali['created_at'])),
						"inv_no" => str_pad($totali['prod_invoice_id'], 6, '0', STR_PAD_LEFT),
						"product" => $prodInfo['name'],
						"unit_price" => 'PHP '.number_format($current_price['price'], 2),
						"qty" => $totali['qty'],
						"subtotal" => 'PHP '.number_format(($current_price['price'] * $totali['qty']), 2),
					);
			}

			foreach ($byDate as $byDatei) {
				$dayTotal = 0;
				$result = ProductSold::where(DB::raw('DATE(created_at)'),'=',$byDatei)->get();
				foreach ($result as $resulti) {
					$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
				}
				$allTotal += $dayTotal;
				$response[] = [$byDatei,$dayTotal];
			}
		}
		$dateprint = date('Y-m-d h:i:sa');
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("reports.printSales")->with("userInfo",$userInfo)->with("products",$products)->with("allTotal",number_format($allTotal, 2))->with('mt','sr')->with("dateprint",$dateprint);
	}

	public function generateSalesReport()
	{
		$byDate = array();
		$response = array();
		$allTotal = 0;
		$date = Input::get('date');
		if(!empty($date))
		{
			$daysEx = explode("-",$date);
			$startdate = date('Y-m-d',strtotime($daysEx[0]));
			$enddate = date('Y-m-d',strtotime($daysEx[1]));
		}
		else
		{
			$startdate = date('Y-m-01');
			$enddate = date("Y-m-d");
		}

		$total = ProductSold::where(DB::raw('DATE(created_at)'),'>=',$startdate)->where(DB::raw('DATE(created_at)'),'<=',$enddate)->get();

		if(!empty($total))
		{
			foreach ($total as $totali) {
				if(!in_array(date('Y-m-d',strtotime($totali['created_at'])),$byDate))
				{
					$byDate[] = date('Y-m-d',strtotime($totali['created_at']));
				}
			}

			foreach ($byDate as $byDatei) {
				$dayTotal = 0;
				$result = ProductSold::where(DB::raw('DATE(created_at)'),'=',$byDatei)->get();
				foreach ($result as $resulti) {
					$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
				}
				$allTotal += $dayTotal;
				$response[] = [$byDatei,$dayTotal];
			}
		}

		return array(
				"response" => $response,
				"dateRange" => "for ".$startdate." to ".$enddate ,
				"allTotal" => "Total Sales: PHP ".number_format($allTotal, 2),
				"printTarget" => URL::Route('printSales',[$startdate,$enddate]),
			);

	}
}