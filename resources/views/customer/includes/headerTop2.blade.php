<div class="headertop_desc">
	<div class="call">
		 <p><span>Need help?</span> call us <span class="number">(02) 842-2782</span></span></p>
	</div>
	<div class="account_desc">
		<ul>
			@if(Auth::Check())
				<li><a href="{{URL::Route('getLogout')}}">Log out</a></li>
				<li><a href="#">Checkout</a></li>
				<li><a href="#">My Account</a></li>
			@else
				<li><a href="javascript:void(0);" id="btn_registration">Register</a></li>
				<li><a href="javascript:void(0);" id="btn_login">Login</a></li>
			@endif
		</ul>
	</div>
	<div class="clear"></div>
</div>