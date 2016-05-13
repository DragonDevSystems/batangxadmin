<div class="headertop_desc">
	<div class="call">
		<?php 
    			$aboutInfo = App::make("App\Http\Controllers\AboutController")->getAboutInformation();
    		 ?>
		 <p><span>Need help?</span> call us <span class="number">{{$aboutInfo['number']}}</span></span></p>
	</div>
	<div class="account_desc">
		<ul>
			@if(Auth::Check())
				<?php $userInfo = App::make("App\Http\Controllers\GlobalController")->userInfoList(Auth::User()['id']); ?>
				<li>Hi! {{$userInfo['fname']}}</li>
				<li><a href="{{URL::Route('getLogout')}}">Sign out</a></li>
				<li><a href="{{URL::Route('getCheckOut')}}">Checkout</a></li>
				<li><a href="{{URL::Route('getMyaccount')}}">My Account</a></li>
			@else
				<li><a href="javascript:void(0);" class="btn_registration">Register</a></li>
				<li><a href="javascript:void(0);" class="btn_login">Sign In</a></li>
			@endif
		</ul>
	</div>
	<div class="clear"></div>
</div>