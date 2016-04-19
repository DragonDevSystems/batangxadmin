@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		<div class="headertop_desc">
			<div class="call">
				 <p><span>Need help?</span> call us <span class="number">1-22-3456789</span></span></p>
			</div>
			<div class="account_desc">
				<ul>
					<li><a href="#">Register</a></li>
					<li><a href="#">Login</a></li>
					<li><a href="#">Delivery</a></li>
					<li><a href="#">Checkout</a></li>
					<li><a href="#">My Account</a></li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	@include('customer.includes.headerTop')
 	@include('customer.includes.mainMenu')
	<div class="header_slide">
	@include('customer.includes.categories')
	@include('customer.includes.productSlider')
		   <div class="clear"></div>
		</div>
   </div>
 <div class="main">
    <div class="content">
    @include('customer.includes.newProduct')

    </div>
 </div>
</div>
	@include('customer.includes.footer')
	@include('customer.user.login')
	@include('customer.user.registration')
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