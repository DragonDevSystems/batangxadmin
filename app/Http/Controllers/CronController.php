<?php 
namespace App\Http\Controllers;

use App\Models\ProductInvoice;
use App\Models\ProductReserve;
use App\Models\ProductInventory;
class CronController extends Controller {

	public function checkExpireReservation()
	{
		$invoice = ProductInvoice::where("status","=",1)->where('created_at', '<=', \Carbon\Carbon::now()->subDay())->get();
		if(!empty($invoice))
		{
			foreach ($invoice as $invoicei) {
				$cus_id = $invoicei['cus_id'];
				$inv_id = $invoicei['id'];
				$type = 4;
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
			}
			
		}
		
	}
}