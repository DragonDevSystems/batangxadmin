<?php 
namespace App\Http\Controllers;

use View;
use Auth;
use App;
use App\Models\ProCategory;
use Input;
use Response;

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
		$cid = Input::get('cid');
		$status = Input::get('status');
    	$name = Input::get('name');
    	$description = Input::get('description');

    	$procategory = (!empty($cid)) ? ProCategory::find($cid) :new ProCategory();
    	if(!empty($procategory))
    	{
	    	$procategory['name'] = $name;
	    	$procategory['description'] = $description;
	    	$procategory['status'] = (!empty($status)) ? $status : 0;
	    	if($procategory->save())
	    	{
	    		return Response::json(array(
	                'status'  => 'success',
	                'message'  => 'You succesfully added new category.',
	            ));
	    	}
    	}
    	return Response::json(array(
            'status'  => 'fail',
            'message'  => 'An error occured while creating the new category. Please try again. .',
        ));
	}
}