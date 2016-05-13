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
use App\Models\ProductDeliveryReceipt;

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

	public function printSales($sdate,$edate,$year,$type)
	{
		$byDate = array();
		$response = array();
		$products = array();

		switch ($type) {
			case 0:
				$allTotal = 0;
				$total = ProductSold::where(DB::raw('DATE(created_at)'),'>=',$sdate)->where(DB::raw('DATE(created_at)'),'<=',$edate)->get();
				break;
			case 1:
				$allTotal = 0;
				$total = ProductSold::whereYear('created_at','=',$year)->get();
				break;
			case 2:
				$allTotal = 0;
				$total = ProductSold::all();
				break;
			default:
				# code...
				break;
		}
		
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
		$rtype = Input::get('rtype');
		$year = Input::get('year');
		$headerL = null;
		$printUrl = null;
		switch ($rtype) {
			case 0:
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

					$dateRange = $this->createDateRangeArray($startdate,$enddate);
					$total = ProductSold::where(DB::raw('DATE(created_at)'),'>=',$startdate)->where(DB::raw('DATE(created_at)'),'<=',$enddate)->get();

					if(!empty($total))
					{
						foreach ($total as $totali) {
							if(!in_array(date('Y-m-d',strtotime($totali['created_at'])),$byDate))
							{
								$byDate[] = date('Y-m-d',strtotime($totali['created_at']));
							}
						}

						foreach ($dateRange as $dateRangei) {
							$dayTotal = 0;
							$result = ProductSold::where(DB::raw('DATE(created_at)'),'=',$dateRangei)->get();
							if(!empty($result))
							{
								foreach ($result as $resulti) {
									$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
								}
							}

							$allTotal += $dayTotal;
							$response[] = [$dateRangei,$dayTotal];
						}
					}

					$headerL = "for ".$startdate." to ".$enddate ;
					$printUrl = URL::Route('printSales',[$startdate,$enddate,0,0]);
				break;
			case 1:
				$monthRange = [[1,'January'],[2,'February'],[3,'March'],[4,'April'],[5,'May'],[6,'June'],[7,'July'],[8,'August'],[9,'September'],[10,'October'],[11,'November'],[12,'December']];
				$total = ProductSold::whereYear('created_at','=',$year)->get();
				if(!empty($total))
				{
					foreach ($monthRange as $monthRangei) {
						$dayTotal = 0;
						$result = ProductSold::whereMonth('created_at','=',$monthRangei[0])->get();
						if(!empty($result))
						{
							foreach ($result as $resulti) {
								$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
							}
						}
						$allTotal += $dayTotal;
						$response[] = [$monthRangei[1],$dayTotal];
					}
				}
				$headerL = "for ".$year;
				$printUrl = URL::Route('printSales',[0,0,$year,1]);
			break;
				case 2:
				$yearRange = ['2016'];
				foreach ($yearRange as $yearRangei) {
					$dayTotal = 0;
					$result = ProductSold::whereYear('created_at','=',$yearRangei)->get();
					if(!empty($result))
					{
						foreach ($result as $resulti) {
							$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
						}
					}
					$allTotal += $dayTotal;
					$response[] = [$yearRangei,$dayTotal];
				}
				$headerL = "for ".$year;
				$printUrl = URL::Route('printSales',[0,0,0,2]);
				break;
			default:
				# code...
				break;
		}

		return array(
				"response" => $response,
				"dateRange" => $headerL,
				"allTotal" => "Total Sales: PHP ".number_format($allTotal, 2),
				"printTarget" => $printUrl,
			);

	}

	public function createDateRangeArray($strDateFrom,$strDateTo)
	{
	    // takes two dates formatted as YYYY-MM-DD and creates an
	    // inclusive array of the dates between the from and to dates.

	    // could test validity of dates here but I'm already doing
	    // that in the main script

	    $aryRange=array();

	    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	    if ($iDateTo>=$iDateFrom)
	    {
	        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	        while ($iDateFrom<$iDateTo)
	        {
	            $iDateFrom+=86400; // add 24 hours
	            array_push($aryRange,date('Y-m-d',$iDateFrom));
	        }
	    }
	    return $aryRange;
	}

	public function getDeliveryReportView()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("reports.delivery")->with("userInfo",$userInfo)->with('mt','dr');
	}

	public function generateDeliveryReport()
	{
		$byDate = array();
		$response = array();
		$allTotal = 0;
		$date = Input::get('date');
		$rtype = Input::get('rtype');
		$year = Input::get('year');
		$headerL = null;
		$printUrl = null;
		switch ($rtype) {
			case 0:
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

					$dateRange = $this->createDateRangeArray($startdate,$enddate);
					$total = ProductDeliveryReceipt::where(DB::raw('DATE(created_at)'),'>=',$startdate)->where(DB::raw('DATE(created_at)'),'<=',$enddate)->get();
					if(!empty($total))
					{
						foreach ($total as $totali) {
							if(!in_array(date('Y-m-d',strtotime($totali['created_at'])),$byDate))
							{
								$byDate[] = date('Y-m-d',strtotime($totali['created_at']));
							}
						}

						foreach ($dateRange as $dateRangei) {
							$dayTotal = 0;
							//$result = ProductSold::where(DB::raw('DATE(created_at)'),'=',$dateRangei)->get();
							$result = ProductDeliveryReceipt::where(DB::raw('DATE(created_at)'),'=',$dateRangei)->get();
							if(!empty($result))
							{
								foreach ($result as $resulti) {
									//$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
									$dayTotal += (count(ProductDeliveryReceipt::find($resulti['id'])));
								}
							}

							$allTotal += $dayTotal;
							$response[] = [$dateRangei,$dayTotal];
						}
					}

					$headerL = "for ".$startdate." to ".$enddate ;
					$printUrl = URL::Route('printSales',[$startdate,$enddate,0,0]);
				break;
			case 1:
				$monthRange = [[1,'January'],[2,'February'],[3,'March'],[4,'April'],[5,'May'],[6,'June'],[7,'July'],[8,'August'],[9,'September'],[10,'October'],[11,'November'],[12,'December']];
				$total = ProductDeliveryReceipt::whereYear('created_at','=',$year)->get();
				if(!empty($total))
				{
					foreach ($monthRange as $monthRangei) {
						$dayTotal = 0;
						$result = ProductDeliveryReceipt::whereMonth('created_at','=',$monthRangei[0])->get();
						if(!empty($result))
						{
							foreach ($result as $resulti) {
								//$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
								$dayTotal += (count(ProductDeliveryReceipt::find($resulti['id'])));
							}
						}
						$allTotal += $dayTotal;
						$response[] = [$monthRangei[1],$dayTotal];
					}
				}
				$headerL = "for ".$year;
				$printUrl = URL::Route('printSales',[0,0,$year,1]);
			break;
				case 2:
				$yearRange = ['2016'];
				foreach ($yearRange as $yearRangei) {
					$dayTotal = 0;
					$result = ProductDeliveryReceipt::whereYear('created_at','=',$yearRangei)->get();
					if(!empty($result))
					{
						foreach ($result as $resulti) {
							//$dayTotal += (ProductPrice::find($resulti['price_id'])['price'] * $resulti['qty']);
							$dayTotal += (count(ProductDeliveryReceipt::find($resulti['id'])));
						}
					}
					$allTotal += $dayTotal;
					$response[] = [$yearRangei,$dayTotal];
				}
				$headerL = "for ".$year;
				$printUrl = URL::Route('printSales',[0,0,0,2]);
				break;
			default:
				# code...
				break;
		}

		return array(
				"response" => $response,
				"dateRange" => $headerL,
				"allTotal" => "Total Sales: PHP ".number_format($allTotal, 2),
				"printTarget" => $printUrl,
			);

	}
}