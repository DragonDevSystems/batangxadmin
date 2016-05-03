<div class="footer">
	  <div class="wrap">	
     <div class="section group">
			<div class="col_1_of_4 span_1_of_4">
					<h4>Information</h4>
					<ul>
					<li><a href="{{ URL::Route('getAbout') }}">About Us</a></li>
					<li><a href="{{ URL::Route('getNews') }}">News</a></li>
					<li><a href="{{ URL::Route('getContactUs') }}">Contact Us</a></li>
					</ul>
				</div>
			<div class="col_1_of_4 span_1_of_4">
				<h4>My account</h4>
					<ul>
						@if(Auth::Check())
							<li><a href="{{URL::Route('getLogout')}}">Sign Out</a></li>
							<li><a href="{{URL::Route('getCheckOut')}}">Checkout</a></li>
							<li><a href="{{URL::Route('getMyaccount')}}">My Account</a></li>
						@else
							<li><a href="javascript:void(0);" class="btn_registration">Register</a></li>
							<li><a href="javascript:void(0);" class="btn_login">Sign In</a></li>
						@endif
					</ul>
			</div>
			<div class="col_1_of_4 span_1_of_4">
				<h4>Contact</h4>
					<ul>
						<li><span>gxtremephtestpp@gmail.com</span></li>
						<li><span>(02) 842-2782</span></li>
					</ul>
					<div class="social-icons">
						<h4>Follow Us</h4>
				   		  <ul>
						      <li><a href="https://www.facebook.com/GamextremePhilippines" target="_blank"><img src="{{env('FILE_PATH_CUSTOM')}}img/facebook.png" alt="" /></a></li>
						      <li><a href="http://www.ebay.ph/usr/gamextremephils" target="_blank"><img src="{{env('FILE_PATH_CUSTOM')}}img/ebay.png" alt="" /></a></li>
						      <div class="clear"></div>
					     </ul>
	 					</div>
			</div>
			<div class="col_1_of_4 span_1_of_4">
				<div style="width:300px">
					<h4>Tell Us your testimonial</h4>
					@if(Auth::Check())
						<div class="contact-form" >
							<form method="post" action="{{URL::Route('postTestimonial')}}">
								<ul>
							    	<span><textarea cols="50" id="testimonial" name="testimonial" required></textarea></span>
							   		<li><input type="hidden" value="{{ csrf_token() }}" name="_token"></li>
								</ul>
								<div class="social-icons">
									<span><input type="submit" value="Submit" class="myButton"></span>
								</div>
							</form>
						</div>
					@else
						<ul>
							<li><span>You must be logged in to submit a testimonial.</span></li>
						</ul>
					@endif
				</div>
			</div>
		</div>			
    </div>
    <div class="copy_right">
		<p>GameXtreme © All rights Reseverd</p>
   </div>
</div>