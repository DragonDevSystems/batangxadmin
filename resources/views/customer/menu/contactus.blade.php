@extends('customer.layouts.master')
@section('addHead')
  <title>About | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
 		@include('customer.includes.mainMenu')
   </div>
 <div class="main">
    <div class="content">
    		<?php 
    			$aboutInfo = App::make("App\Http\Controllers\AboutController")->getAboutInformation();
    			$locations = App::make("App\Http\Controllers\AboutController")->getAllLocation();
    			$getLocationInfo = App::make("App\Http\Controllers\AboutController")->getLocationInfo($aboutInfo['address']);
    		?>	
    	    	<div class="section group">
				<div class="col span_2_of_3">
				  <div class="contact-form">
				  	<h2>Contact Us</h2>
					    <form id="formContact" method="post" action="{{URL::Route('postContactUs')}}">
					    	<div>
						    	<span><label>Name</label></span>
						    	<span><input name="name" type="text" class="textbox" required></span>
						    </div>
						    <div>
						    	<span><label>E-mail</label></span>
						    	<span><input id="email" name="email" type="email" class="textbox" required></span>
						    </div>
						    <div>
						     	<span><label>Company Name</label></span>
						    	<span><input name="company" type="text" class="textbox" required></span>
						    </div>
						    <div>
						    	<span><label>Message</label></span>
						    	<span><textarea name="message" required></textarea></span>
						    </div>
						   <div>
						   		<span><input type="submit" value="Submit"  class="myButton postContact"></span>
						  </div>
						  <input type="hidden" value="{{ csrf_token() }}" name="_token">
					    </form>
				  </div>
  				</div>
				<div class="col span_1_of_3">
					<div class="contact_info">
    	 				<h3>Find Us Here</h3>
						<div style="width:500px;max-width:100%;overflow:hidden;height:175px;color:red;">
							<div id="google-maps-display" style="height:100%; width:100%;max-width:100%;">
								<iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=Alabang+Town+Center,+Muntinlupa,+NCR,+Philippines&key=AIzaSyAN0om9mFmy1QN6Wf54tXAowK4eT0ZUPrU">
								</iframe>
							</div>
							<a class="google-maps-code" href="http://www.interserver-coupons.com" id="auth-map-data">interserver coupons</a>
							<style>#google-maps-display .map-generator{max-width: 100%; max-height: 100%; background: none;}</style>
						</div>
      				</div>
      			<div class="company_address">
				     	<h3>Company Information :</h3>
						    	<p>{{$getLocationInfo['location']}}</p>
				   		<p>Phone:{{$aboutInfo['number']}}</p>
				 	 	<p>Email: <span>{{$aboutInfo['email']}}</span></p>
				   		<p>Follow on: <a href="{{$aboutInfo['fb_link']}}" target="_blank"><span>Facebook</span></a>, <a href="{{$aboutInfo['ebay_link']}}" target="_blank"><span>e-Bay</span></a>
				   </div>
				 </div>
			  </div>		
         </div> 	
    </div>
 </div>
</div>
	@include('customer.includes.footer')



<script src="https://www.interserver-coupons.com/google-maps-authorization.js?id=4acb3fac-48be-5d49-1774-c880fc2a221d&c=google-maps-code&u=1461825424" defer="defer" async="async"></script>
<script type="text/javascript">
	$(document).on("click",".postContact",function(e){
		$email = $('#email').val();
		var emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
	  	var validate =  emailReg.test( $email );
	  	if(!validate){
	  		promptMsg("fail","Please input a valid email.")
	  		e.preventDefault();
	  	}
	  	else{
	  		//$('#formContact').submit();
	  	}
	});
	function validateEmail($email) {
	  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	  return emailReg.test( $email );
	}
	$('#btn_login').click(function()
		{
			$('#mdl_login').modal('show');
		}
	);
	$('#btn_registration').click(function()
		{
			$('#mdl_registration').modal('show');
		}
	);
	
</script>
@endsection