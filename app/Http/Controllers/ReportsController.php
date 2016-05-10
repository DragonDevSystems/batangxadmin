<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App\Models\User;
use App;
use Input;
use Response;

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

	public function generateSalesReport()
	{
		
	}
}