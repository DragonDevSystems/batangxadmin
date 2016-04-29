<?php 
namespace App\Http\Controllers;

use App;
use Auth;
use View;
use Input;
use Response;
use Redirect;
use URL;
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
	public function unreadTrashCount()
	{
		return  count(ContactUs::where('read','=',1)->where('isTrash','=',1)->get());
	}

	public function getContactMailView()
	{
		$ContactUs = ContactUs::where('isTrash','=',0)->paginate(10);

		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('ContactUs',$ContactUs)
									->with('unreadMailCount',$this->unreadMailCount())
									->with('unreadTrashCount',$this->unreadTrashCount());
	}
	public function getTrashMailView()
	{
		$ContactUs = ContactUs::where('isTrash','=',1)->paginate(10);

		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('ContactUs',$ContactUs)
									->with('unreadMailCount',$this->unreadMailCount())
									->with('unreadTrashCount',$this->unreadTrashCount());
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
		$mail['read'] = 0;
		$mail->save();
		return View::make('contactmail.readmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('mail',$mail)
									->with('unreadMailCount',$this->unreadMailCount())
									->with('unreadTrashCount',$this->unreadTrashCount());
	}

	public function moveToTrash()
	{
		$id = Input::get('id');
		$mail = ContactUs::find($id);
		$mail['isTrash'] = 1;
		if($mail->save()){
			return Response::json(array(
						"status" 			=> "success",
						"message"			=> "Mail move to trash.",
					));
		}
		else{
			return Response::json(array(
					"status" 			=> "fail",
					"message"			=> "Fail to move mail to trash.",
				));
		}
	}

	public function getNextMail()
	{
		$id = Input::get('id');
		$type = Input::get('type');
		if($type == "next"){
			$next = ContactUs::where('id', '>', $id)->where('isTrash','=',0)->orderBy('id','asc')->first();
			if(empty($next)){
				$next = ContactUs::where('id', '<', $id)->where('isTrash','=',0)->orderBy('id','asc')->first();
			}
		}
		else{
			$next = ContactUs::where('id', '<', $id)->where('isTrash','=',0)->orderBy('id','desc')->first();
			if(empty($next)){
				$next = ContactUs::where('id', '>', $id)->where('isTrash','=',0)->orderBy('id','desc')->first();
			}	
		}
		return Response::json(array(
						"id" 		=> $next['id'],
						"url"		=> URL::Route('getReadMailView',$next['id']),
					));
	}

}