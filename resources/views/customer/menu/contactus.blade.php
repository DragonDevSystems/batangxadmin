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
    	    	<div class="section group">
				<div class="col span_2_of_3">
				  <div class="contact-form">
				  	<h2>Contact Us</h2>
					    <form method="post" action="{{URL::Route('postContactUs')}}">
					    	<div>
						    	<span><label>Name</label></span>
						    	<span><input name="name" type="text" class="textbox" required></span>
						    </div>
						    <div>
						    	<span><label>E-mail</label></span>
						    	<span><input name="email" type="email" class="textbox" required></span>
						    </div>
						    <div>
						     	<span><label>Company Name</label></span>
						    	<span><input name="company" type="text" class="textbox" required></span>
						    </div>
						    <div>
						    	<span><label>Subject</label></span>
						    	<span><textarea name="message" required> </textarea></span>
						    </div>
						   <div>
						   		<span><input type="submit" value="Submit"  class="myButton"></span>
						  </div>
						  <input type="hidden" value="{{ csrf_token() }}" name="_token">
					    </form>
				  </div>
  				</div>
				<div class="col span_1_of_3">
					<div class="contact_info">
    	 				<h3>Find Us Here</h3>
					    	  <div class="map">
							   	    <iframe width="100%" height="175" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.in/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Lighthouse+Point,+FL,+United+States&amp;aq=4&amp;oq=light&amp;sll=26.275636,-80.087265&amp;sspn=0.04941,0.104628&amp;ie=UTF8&amp;hq=&amp;hnear=Lighthouse+Point,+Broward,+Florida,+United+States&amp;t=m&amp;z=14&amp;ll=26.275636,-80.087265&amp;output=embed"></iframe><br><small><a href="https://maps.google.co.in/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Lighthouse+Point,+FL,+United+States&amp;aq=4&amp;oq=light&amp;sll=26.275636,-80.087265&amp;sspn=0.04941,0.104628&amp;ie=UTF8&amp;hq=&amp;hnear=Lighthouse+Point,+Broward,+Florida,+United+States&amp;t=m&amp;z=14&amp;ll=26.275636,-80.087265" style="color:#666;text-align:left;font-size:12px">View Larger Map</a></small>
							  </div>
      				</div>
      			<div class="company_address">
				     	<h3>Company Information :</h3>
						    	<p>Alabang Town Center,</p>
						   		<p>Alabang-Zapote Road,</p>
						   		<p>Muntinlupa City, Metro Manila</p>
				   		<p>Phone:(02) 842-2782</p>
				 	 	<p>Email: <span>allenjamesxxiv@gmail.com</span></p>
				   		<p>Follow on: <a href="https://www.facebook.com/GamextremePhilippines" target="_blank"><span>Facebook</span></a>, <a href="http://www.ebay.ph/usr/gamextremephils" target="_blank"><span>e-Bay</span></a>
				   </div>
				 </div>
			  </div>		
         </div> 	
    </div>
 </div>
</div>
	@include('customer.includes.footer')
<script type="text/javascript">
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