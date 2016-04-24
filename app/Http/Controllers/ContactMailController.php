<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use App\Models\ContactUs;

class ContactMailController extends Controller {

	public function userInfo()
	{
		return App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']);
	}

	public function unreadMailCount()
	{
		return  count(ContactUs::where('read','=',1)->get());
	}

	public function getContactMailView()
	{
		$ContactUs = ContactUs::paginate(10);

		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('ContactUs',$ContactUs)
									->with('unreadMailCount',$this->unreadMailCount());
	}
	public function getComposeMailView()
	{
		return View::make('contactmail.composemail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('unreadMailCount',$this->unreadMailCount());
	}

	public function getReadMailView($id)
	{
		$mail = ContactUs::find($id);
		return View::make('contactmail.readmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('mail',$mail)
									->with('unreadMailCount',$this->unreadMailCount());
	}

}