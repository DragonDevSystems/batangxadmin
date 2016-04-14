<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use Shinobi;
use App;
use App\Models\ProCategory;

class FileMaintenanceController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getCategory()
	{
		$userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
		return View::Make("filemaintenance.category")->with("userInfo",$userInfo)->with('mt','fm')->with('cc','pc');
	}
	
	public function categoryList()
	{
		return ProCategory::all();
	}

	public function addCategory()
	{
    	$name = Input::get('name');
    	$description = Input::get('description');

    	$procategory = new ProCategory();
    	$procategory['name'] = $name;
    	$procategory['description'] = $description;
    	if($procategory->save())
    	{
    		return Response::json(array(
                'status'  => 'success',
                'message'  => 'You succesfully added new permission.',
            ));
    	}
    	else
    	{
	    	return Response::json(array(
                'status'  => 'fail',
                'message'  => 'An error occured while creating the new permission. Please try again. .',
            ));
    	}
	}
}