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
		return  count(ContactUs::where('read','=',0)->get());
	}
	public function unreadTrashCount()
	{
		return  count(ContactUs::where('read','=',0)->where('isTrash','=',1)->get());
	}

	public function getContactMailView()
	{
		$ContactUs = ContactUs::where('isTrash','=',0)->paginate(10);

		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('mm','inbox')
									->with('ContactUs',$ContactUs)
									->with('unreadMailCount',$this->unreadMailCount())
									->with('unreadTrashCount',$this->unreadTrashCount());
	}
	public function getTrashMailView()
	{
		$ContactUs = ContactUs::where('isTrash','=',1)->paginate(10);

		return View::make('contactmail.contactmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('mm','trash')
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

	public function getReadMailView($type,$id)
	{
		$mail = ContactUs::find($id);
		$mail['read'] = 1;
		$mail->save();
		if($type == "inbox"){
			$count = count(ContactUs::where('isTrash','=',0)->get());
		}
		else{
			$count = count(ContactUs::where('isTrash','=',1)->get());
		}
		
		return View::make('contactmail.readmail')->with("userInfo",$this->userInfo())
									->with('mt',"ml")
									->with('mm',$type)
									->with('mail',$mail)
									->with('count',$count)
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
		$type_for = Input::get('type_for');
		if($type == "next"){
			if($type_for == "inbox"){
				$next = ContactUs::where('id', '>', $id)->where('isTrash','=',0)->orderBy('id','asc')->first();
				if(empty($next)){
					$next = ContactUs::where('id', '<', $id)->where('isTrash','=',0)->orderBy('id','asc')->first();
				}
			}
			else{
				$next = ContactUs::where('id', '>', $id)->where('isTrash','=',1)->orderBy('id','asc')->first();
				if(empty($next)){
					$next = ContactUs::where('id', '<', $id)->where('isTrash','=',1)->orderBy('id','asc')->first();
				}
			}
			
		}
		else{
			if($type_for == "inbox"){
				$next = ContactUs::where('id', '<', $id)->where('isTrash','=',0)->orderBy('id','desc')->first();
				if(empty($next)){
					$next = ContactUs::where('id', '>', $id)->where('isTrash','=',0)->orderBy('id','desc')->first();
				}
			}
			else{
				$next = ContactUs::where('id', '<', $id)->where('isTrash','=',1)->orderBy('id','desc')->first();
				if(empty($next)){
					$next = ContactUs::where('id', '>', $id)->where('isTrash','=',1)->orderBy('id','desc')->first();
				}

			}
				
		}
		return Response::json(array(
						"id" 		=> $next['id'],
						"url"		=> URL::Route('getReadMailView',[$type_for,$next['id']]),
					));
	}

}