<?php 
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\User;
use App\Models\Online;
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
use Auth;
use DB;
use Input;
use Response;
use Request;
use View;
use URL;

class GlobalController extends Controller {

	public function userInfo($id)
	{
		return Info::where('user_id','=',$id)->first();
	}

	public function userInfoList($uid)
	{
		$id = (!empty($uid)) ? $uid : Input::get("uid");
		$userInfo = $this->userInfo($id);
		$username = User::find($id);
		if(!empty($userInfo))
		{
			return array(
						"user_id" 		=> $userInfo['user_id'],
						"acct_no" 		=> str_pad($userInfo['user_id'], 6, '0', STR_PAD_LEFT),
						"mobile" 		=> $userInfo['mobile'],
						"email" 		=> $userInfo['email'],
						"un"			=> $username['username'],
						"userDp"		=> $this->userDpv2($userInfo['user_id']),
						"fname"			=> $userInfo['first_name'],
						"lname"			=> $userInfo['last_name'],
						"dm"			=> $userInfo['created_at'],
						"lvl"			=> $username['isAdmin'],
						"dob"			=> date("m/d/Y", strtotime($userInfo['dob'])),
						"gender"		=> $userInfo['gender'],
						"address"		=> $userInfo['address'],
						"isVerified"	=> $username['isVerified'],
						"blocked"		=> $username['blocked'],
					);
		}
	}

	public function userDP($id)
	{
		return User::find($id)['userImages'];
	}

	public function allProduct()
	{
		return ProductInformation::all();
	}

	public function accountAccessChecker($event,$module)
	{
		$event = (!empty($event)) ? $event : Input::get('event');
		$module = (!empty($module)) ? $module : Input::get('module');
		$uaccess = Auth::User()['isAdmin'];
		switch ($module) {
		    case "uam":
			        if(($uaccess == 2 || $uaccess == 3) && ($event == "add" || $event == "update" || $event == "view"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}

					if(($uaccess == 3) && ($event == "delete"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}

					return array(
	                	'status'  => 'fail',
	                	'message'  => 'You have no permission to add, update, and delete data or records.',
	            	);
		        break;
		    case "product":
		        	if(($uaccess == 2 || $uaccess == 3) && ($event == "add" || $event == "update" || $event == "view"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}

					if(($uaccess == 3) && ($event == "delete"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}
					
					return array(
	                	'status'  => 'fail',
	                	'message'  => 'You have no permission to add, update, and delete data or records.',
	            	);
		        break;
		    case "fm":
		         if(($uaccess == 2 || $uaccess == 3) && ($event == "add" || $event == "update" || $event == "view"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}

					if(($uaccess == 3) && ($event == "delete"))
					{
						return array(
			                'status'  => 'success',
			                'message'  => 'Permission granted.',
			            );
					}
					return array(
	                	'status'  => 'fail',
	                	'message'  => 'You have no permission to add, update, and delete data or records.',
	            	);
		        break;
		    default:
			        return array(
	                	'status'  => 'fail',
	                	'message'  => 'You have no permission to add, update, and delete data or records.',
	            	);
            	break;
        	
		}
		/*if(($uaccess == 2 || $uaccess == 3) && ($event == "add" || $event == "update" || $event == "delete"))
		{
			return array(
                'status'  => 'success',
                'message'  => 'Permission granted.',
            );
		}
		else
		{
			return array(
                'status'  => 'fail',
                'message'  => 'You have no permission to add, update, and delete data or records.',
            );
		}*/
	}

	public function userDpv2($id)
	{
		$checkDP = UserImage::where('user_id','=',$id)
											->where('dp','=',1)
												->first();
		if(!empty($checkDP)){
			return "userImage/".$checkDP['img_thumbnail'];
		}
		return "img/no-profile.png";//.$userDPics;
	}

	public function statsbox()
	{
		return Response::json(
						[array(
								"count" =>count($this->newUser()),
								"bg_color" => "bg-red",
								"content_title" => "New Users This Month",
								"Ionicons" => "",//"ion-person-add",
								"link" => URL::Route('statsList','NU'),
							),//new user this month
						array(
								"count"	=> count($this->onlineUser()),
								"bg_color" => "bg-red",
								"content_title" => "Online User",
								"Ionicons" => "",//"ion-stats-bars",
								"link" => URL::Route('statsList','OU'),
							),// online user near realtime
						array(
								"count"	=> count($this->registeredUser()),
								"bg_color" => "bg-red",
								"content_title" => "Registered User",
								"Ionicons" => "",//"ion-person-add",
								"link" => URL::Route('statsList','RU'),
							),// total of registered user
						array(
								"count"	=> count($this->unverifiedUser()),
								"bg_color" => "bg-red",
								"content_title" => "Unverified User",
								"Ionicons" => "",//"ion-pie-graph",
								"link" => URL::Route('statsList','UVU'),
							),//un-verified user
						array(
								"count"	=> count($this->unReadMessage()),
								"bg_color" => "bg-red",
								"content_title" => "Unread Inquiries",
								"Ionicons" => "",//"ion-android-mail",
								"link" => URL::Route('getContactMailView'),
							),//un-read message from contact us
						array(
								"count"	=> count($this->onCartProduct()),
								"bg_color" => "bg-red",
								"content_title" => "On Cart Product",
								"Ionicons" => "",//"ion-android-cart",
								"link" => URL::Route('statsList','OCP'),
							),//On cart product
						]);

	}

	public function onCartProduct()
	{
		try
		{
			Online::updateCurrent();
			return ProductOnCart::all();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function unReadMessage()
	{
		try
		{
			Online::updateCurrent();
			return ContactUs::where('read','=',0)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function categoryInfo($cid)
	{
		$id = (!empty($cid)) ? $cid : Input::get("cid");
		return ProCategory::find($id);
	}

	public function categoryList()
	{
		return ProCategory::where("status","!=",0)->get();
	}
	public function onlineUser()
	{
		try
		{
			Online::updateCurrent();
			return Online::whereNotNull('user_id')->select('user_id')->groupBy('user_id')->get()->toArray();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function registeredUser()
	{
		try 
		{
			return User::where('isVerified','=',1)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function unverifiedUser()
	{
		try 
		{
			return User::where('isVerified','=',0)->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	public function newUser()
	{
		//for current month only
		try 
		{
			return User::where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->get();
		}catch (\Exception $e){
        	return 'Sorry something went worng. Please try again.';
   		}
	}

	//resize image to default max width or high
	public function imageResized($width,$height,$size)
	{
		if($width <= $size && $height <= $size)
		{
			return array(
					"width"	=> $width,
					"height" => $height,
				);
		}
		else
		{
			$percentagetoLess = ($width > $height) ? ((($width - $size)/$width)*100) : ((($height - $size)/$height)*100);
			return array(
					"width"	=> intval($width * ((100-$percentagetoLess) / 100)),
					"height" => intval($height * ((100-$percentagetoLess) / 100)),
				);
		}
	}

	public function userlist()
	{
		$response = array();
		$userList = User::where('isAdmin','=',0)->get();//where('id','!=',Auth::User()['id'])->where('isAdmin','=',0)->get();
		//return $userList;
		if(!empty($userList))
		{
			foreach ($userList as $userListi) {
				$response[] = $this->userInfoList($userListi['id']);
			}

			return Response::json(array(
	            'status'  => 'success',
	            'userInfoList'  => $response,
	        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The user record is empty or all user has been listed in the admin account.',
	        ));
		}
	}

	public function cuslist()
	{
		$response = array();
		$userList = User::all();//where('id','!=',Auth::User()['id'])->where('isAdmin','=',0)->get();
		//return $userList;
		if(!empty($userList))
		{
			foreach ($userList as $userListi) {
				$response[] = $this->userInfoList($userListi['id']);
			}

			return Response::json(array(
	            'status'  => 'success',
	            'userInfoList'  => $response,
	        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The user record is empty or all user has been listed in the admin account.',
	        ));
		}
	}

	public function topNewProduct($take)
	{
		$response = array();
		$topNewProduct = ProductInformation::take($take)->orderBy('created_at','desc')->get();
		foreach ($topNewProduct as $topNewProducti) {
			$images = ProductImage::where('prod_id','=',$topNewProducti['id'])->orderByRaw("RAND()")->first();
			$proPrice = ProductPrice::where('prod_id','=',$topNewProducti['id'])->where('status','=',1)->first();
			if(!empty($proPrice))
			{
				$response[] = array(
					"productInfo" => $topNewProducti,
					"productPrice" => (!empty($proPrice)) ? 'PHP '.number_format($proPrice['price'], 2) : "Price N/A" ,
					"pro_img" => $images
				);
			}
		}
		return $response;
	}

	public function relatedProduct($take,$cat_id)
	{
		$response = array();
		$topNewProduct = ProductInformation::take($take)->orderBy('created_at','desc')->where('pro_cat_id','=',$cat_id)->get();
		foreach ($topNewProduct as $topNewProducti) {
			$images = ProductImage::where('prod_id','=',$topNewProducti['id'])->orderByRaw("RAND()")->first();
			$proPrice = ProductPrice::where('prod_id','=',$topNewProducti['id'])->where('status','=',1)->first();
			if(!empty($proPrice))
			{
				$response[] = array(
					"productInfo" => $topNewProducti,
					"productPrice" => (!empty($proPrice)) ? 'PHP '.number_format($proPrice['price'], 2) : "Price N/A" ,
					"pro_img" => $images
				);
			}
		}
		return $response;
	}

	public function featuredProduct()
	{
		$response = array();
		$featuredProduct = ProductInformation::where('isFeatured','=',1)->orderBy('updated_at','desc')->get();
		foreach ($featuredProduct as $product) {
			$images = ProductImage::where('prod_id','=',$product['id'])->orderByRaw("RAND()")->first();
			$proPrice = ProductPrice::where('prod_id','=',$product['id'])->where('status','=',1)->first();
			if(!empty($proPrice))
			{
				$response[] = array(
					"productInfo" => $product,
					"productPrice" => (!empty($proPrice)) ? 'PHP '.number_format($proPrice['price'], 2) : "Price N/A" ,
					"pro_img" => $images
				);
			}
		}
		return $response;
	}

	public function auditTrail($action,$dataId,$details)
	{
		$ip = Request::ip();

		$insert = new AuditTrail();
		$insert['action'] = $action;
		$insert['data_id'] = $dataId;
		$insert['user_id'] = Auth::User()['id'];
		$insert['details'] = $details;
		$insert['ip_address'] = $ip;
		return (!$insert->save()) ? false : true ;
	}

	public function availabilityCheck($pid)
	{	
		$check = ProductInventory::where("prod_id","=",$pid)->first()['qty'];
		return (!empty($check)) ? $check : 0;
	}

	public function productOnCart()
	{
		$type = Input::get('type');
		$response = array();
		$products = array();
		$check = ProductOnCart::where("cus_id","=",Auth::User()['id'])->where("type","=",$type)->get();
		$totalP = 0;
		$totalQty = 0;
		if(!empty($check))
		{
			foreach ($check as $checki) {
				$productPrice = ProductPrice::where("prod_id","=",$checki['prod_id'])->where("status","=",1)->first();
				if(!empty($productPrice))
				{
					$totalP += ($productPrice['price'] * $checki['qty']);
				}
				$totalQty += $checki['qty'];
			}
			$response[] = array(
					"totalPrice" => 'PHP '.number_format($totalP, 2),
					"totalQty" => $totalQty,
				);
		}
		return $response;
	}

	public function onCartList($cus_id,$type)
	{
		$response = array();
		$products = array();
		$cus_id = (!empty($cus_id)) ? $cus_id : Input::get('cus_id');
		$check = ProductOnCart::where("cus_id","=",$cus_id)->where("type","=",$type)->get();
		$totalP = 0;
		$totalQty = 0;
		if(!empty($check))
		{
			foreach ($check as $checki) {
				$productPrice = ProductPrice::where("prod_id","=",$checki['prod_id'])->where("status","=",1)->first();
				$prodInfo = ProductInformation::find($checki['prod_id']);
				if(!empty($prodInfo))
				{
					$products[] = array(
							"cart_id" => $checki['id'],
							"prod_id" => $prodInfo['id'],
							"name" => $prodInfo['name'],
							"qty" => $checki['qty'],
							"unit_price" =>'PHP '.number_format($productPrice['price'], 2),
							"price" => 'PHP '.number_format(($productPrice['price'] * $checki['qty']), 2),
						);
					$totalQty += $checki['qty'];
					$totalP += ($productPrice['price'] * $checki['qty']);
				}
			}
			$response[] = array(
				"productInfo" => $products,
				"totalPrice" => 'PHP '.number_format($totalP, 2),
				"totalQty" => $totalQty,
				"price_figure" => $totalP
			);
		}
		return $response;
	}

	public function onReserveList($cus_id,$invoiceId)
	{
		$response = array();
		$products = array();
		$check = ProductReserve::where("cus_id","=",$cus_id)->where("prod_invoice_id","=",$invoiceId)->get();
		$totalP = 0;
		$totalQty = 0;
		if(!empty($check))
		{
			foreach ($check as $checki) {
				$productPrice = ProductPrice::where("prod_id","=",$checki['prod_id'])->where("status","=",1)->first();
				$prodInfo = ProductInformation::find($checki['prod_id']);
				if(!empty($prodInfo))
				{
					$products[] = array(
							"cart_id" => $checki['id'],
							"prod_id" => $prodInfo['id'],
							"name" => $prodInfo['name'],
							"qty" => $checki['qty'],
							"unit_price" =>'PHP '.number_format($productPrice['price'], 2),
							"price" => 'PHP '.number_format(($productPrice['price'] * $checki['qty']), 2),
						);
					$totalQty += $checki['qty'];
					$totalP += ($productPrice['price'] * $checki['qty']);
				}
			}
			$response[] = array(
				"productInfo" => $products,
				"totalPrice" => 'PHP '.number_format($totalP, 2),
				"totalQty" => $totalQty,
			);
		}
		return $response;
	}

	public function onSoldList($cus_id,$invoiceId)
	{
		$response = array();
		$products = array();
		$check = ProductSold::where("cus_id","=",$cus_id)->where("prod_invoice_id","=",$invoiceId)->get();
		$totalP = 0;
		$totalQty = 0;
		if(!empty($check))
		{
			foreach ($check as $checki) {
				$productPrice = ProductPrice::where("prod_id","=",$checki['prod_id'])->where("status","=",1)->first();
				$prodInfo = ProductInformation::find($checki['prod_id']);
				if(!empty($prodInfo))
				{
					$products[] = array(
							"cart_id" => $checki['id'],
							"prod_id" => $prodInfo['id'],
							"name" => $prodInfo['name'],
							"qty" => $checki['qty'],
							"unit_price" =>'PHP '.number_format($productPrice['price'], 2),
							"price" => 'PHP '.number_format(($productPrice['price'] * $checki['qty']), 2),
						);
					$totalQty += $checki['qty'];
					$totalP += ($productPrice['price'] * $checki['qty']);
				}
			}
			$response[] = array(
				"productInfo" => $products,
				"totalPrice" => 'PHP '.number_format($totalP, 2),
				"totalQty" => $totalQty,
			);
		}
		return $response;
	}

	public function removeOnCart()
	{
		$cart_id = Input::get('cid');
		$cartInfo = ProductOnCart::where("id","=",$cart_id)->where("cus_id","=",Auth::User()['id'])->first();
		if(!empty($cartInfo))
		{
			$update = ProductInventory::where("prod_id","=",$cartInfo['prod_id'])->first();
			if(!empty($update))
			{
				$update['qty'] = $update['qty'] + $cartInfo['qty'];
				if($update->save() && $cartInfo->delete())
				{
					return Response::json(array(
			            'status'  => 'success',
			            'message'  => 'Successfully removed from your cart.',
			        ));
				}
			}
			return Response::json(array(
				            'status'  => 'fail',
				            'message'  => 'Fail to remove the particular item in your cart due to connection problem. Please try again.',
				        ));
		}
		else
		{
			return Response::json(array(
	            'status'  => 'fail',
	            'message'  => 'The item that you are trying to remove in your cart is not yet in you cart list.',
	        ));
		}
	}

	public function statsList($entry)
	{
		$userInfo = $this->userInfoList(Auth::User()['id']);
		return View::Make("stats.statsSummary")->with("userInfo",$userInfo)->with('mt','db')->with('entry',$entry);
	}

	public function statsSummary($entry)
	{
		$header = array();
		$datInfo = array();
		switch ($entry) {

			case 'NU':
					$data = ['User_ID','Username','Firstname','Lastname'];
					$header = $data;
					$newUser = $this->newUser();
					$summaryTitle = "New Registered User";
					if(!empty($newUser))
					{
						foreach ($newUser as $newUseri) {
							$userInfo = $this->userInfoList($newUseri['id']);
							$datInfo[] = array(
									0 => $userInfo['user_id'],
									1 => $userInfo['un'],
									2 => $userInfo['fname'],
									3 => $userInfo['lname'],
								);
						}
					}
				break;
			case 'OU':
					$data = ['User_ID','Username','Firstname','Lastname'];
					$header = $data;
					$onLine = $this->onlineUser();
					$summaryTitle = "Online User";
					if(!empty($onLine))
					{
						foreach ($onLine as $onLinei) {
							$userInfo = $this->userInfoList($onLinei['user_id']);
							$datInfo[] = array(
									0 => $userInfo['user_id'],
									1 => $userInfo['un'],
									2 => $userInfo['fname'],
									3 => $userInfo['lname'],
								);
						}
					}
				break;
				case 'RU':
					$data = ['User_ID','Username','Firstname','Lastname'];
					$header = $data;
					$regUser = $this->registeredUser();
					$summaryTitle = "Registered User";
					if(!empty($regUser))
					{
						foreach ($regUser as $regUseri) {
							$userInfo = $this->userInfoList($regUseri['id']);
							$datInfo[] = array(
									0 => $userInfo['user_id'],
									1 => $userInfo['un'],
									2 => $userInfo['fname'],
									3 => $userInfo['lname'],
								);
						}
					}
				break;
				case 'UVU':
					$data = ['User_ID','Username','Firstname','Lastname'];
					$header = $data;
					$regUser = $this->unverifiedUser();
					$summaryTitle = "Unverified User";
					if(!empty($regUser))
					{
						foreach ($regUser as $regUseri) {
							$userInfo = $this->userInfoList($regUseri['id']);
							$datInfo[] = array(
									0 => $userInfo['user_id'],
									1 => $userInfo['un'],
									2 => $userInfo['fname'],
									3 => $userInfo['lname'],
								);
						}
					}
				break;
				case 'OCP':
					$data = ['Prod_id','Customer','Product_Name','Unit_Price','Qty','Total_Price'];
					$header = $data;
					$onCartProduct = $this->onCartProduct();
					$summaryTitle = "Unverified User";
					if(!empty($onCartProduct))
					{
						foreach ($onCartProduct as $onCartProducti) {
							$userInfo = $this->userInfoList($onCartProducti['cus_id']);
							$prodInfo = ProductInformation::find($onCartProducti['prod_id']);
							$current_price = ProductPrice::find($onCartProducti['price_id']);
							$datInfo[] = array(
									0 => $onCartProducti['prod_id'],
									1 => $userInfo['fname'].' '.$userInfo['lname'],
									2 => $prodInfo['name'],
									3 => 'PHP '.number_format($current_price['price'], 2),
									4 => $onCartProducti['qty'],
									5 => 'PHP '.number_format(($current_price['price'] * $onCartProducti['qty']), 2),
								);
						}
					}
				break;
			default:
				# code...
				break;
		}

		return Response::json(array(
	            'status'  => 'success',
	            'header' => $header,
	            'datInfo' => $datInfo,
	            'summaryTitle' => $summaryTitle,
	        ));
	}

	public function invoiceList()
	{
		$response = array();
		$all = ProductInvoice::all();
		if(!empty($all))
		{
			foreach ($all as $alli) {
				$userInfo = $this->userInfoList($alli['cus_id']);
				
				$response[] = array(
						"inv_no" => str_pad($alli['id'], 6, '0', STR_PAD_LEFT),
						"date" => date('Y-m-d',strtotime($alli['created_at'])),
						"customer" => $userInfo['fname'].' '.$userInfo['lname'],
						"remarks" => $alli['remarks'],
						"status" => $this->invoiceStatus($alli['status']),
						"invoice_link" => URL::route('getCheckOutPrint', [$alli['vcode'] , $alli['id']]),
					);
			}
		}
		return $response;
	}

	public function invoiceStatus($statusval)
	{
		switch ($statusval) {
					case 1:
						$status = "Reserved";
						break;
					case 2:
						$status = "Paid";
						break;
					case 3:
						$status = "Cancelled by user";
						break;
					case 4:
						$status = "Expired Invoice";
						break;
					default:
						$status = "Error/No status";
						break;
				}
		return $status;
	}

	public function inventoryList()
	{
		$response = array();
		$all = ProductInformation::all();
		if(!empty($all))
		{
			foreach ($all as $alli) {
				$productOnCart = ProductOnCart::where("prod_id","=",$alli['id'])->sum("qty");
				$productReserve = ProductReserve::where("prod_id","=",$alli['id'])->where("status","=",1)->sum("qty");
				$productSold = ProductSold::where("prod_id","=",$alli['id'])->sum("qty");
				$productInventory = ProductInventory::where("prod_id","=",$alli['id'])->first();
				$response[] = array(
						"prod_code" => str_pad($alli['id'], 6, '0', STR_PAD_LEFT),
						"name" => $alli['name'],
						"on_cart" => (!empty($productOnCart)) ? $productOnCart : 0,
						"reserved" => (!empty($productReserve)) ? $productReserve : 0,
						"sold" => (!empty($productSold)) ? $productSold : 0,
						"avail_item" => (!empty($productInventory)) ? $productInventory['qty'] : 0,
					);
			}
		}
		return $response;
	}

	public function criLvlList()
	{
		$response = array();
		$all = ProductInformation::all();
		if(!empty($all))
		{
			foreach ($all as $alli) {
				$productOnCart = ProductOnCart::where("prod_id","=",$alli['id'])->sum("qty");
				$productReserve = ProductReserve::where("prod_id","=",$alli['id'])->where("status","=",1)->sum("qty");
				$productSold = ProductSold::where("prod_id","=",$alli['id'])->sum("qty");
				$productInventory = ProductInventory::where("prod_id","=",$alli['id'])->first();
				$avail_item = (!empty($productInventory)) ? $productInventory['qty'] : 0;
				if($alli['cri_lvl'] >= $avail_item)
				{
					$response[] = array(
						"prod_code" => str_pad($alli['id'], 6, '0', STR_PAD_LEFT),
						"name" => $alli['name'],
						"cri_lvl" => $alli['cri_lvl'],
						"avail_item" => $avail_item,
						"id" => $alli['id'],
					);
				}
			}
		}
		return $response;
	}
}