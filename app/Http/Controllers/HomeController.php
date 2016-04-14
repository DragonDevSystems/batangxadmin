<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
class HomeController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("home.index")->with("userInfo",$userInfo)->with('mt','db');
	}

}