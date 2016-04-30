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
			<div class="col_1_of_3 span_1_of_3">
				<h3>Who We Are</h3>
				<img src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png" alt="">
				<p>GameXtreme is your one-stop digital hub.
				Offering the latest in mobile and gaming technology, 
				GameXtreme provides you with your gadget needs.</p>
				<p>Based in Philippines. Gamextreme offers high-end Cellphones, 
				Tablets, Gaming consoles, LCD/LED/Smart
				TV?s, Music Player (The Platinum Karaoke) & other Electronic items since 2006 at competitive prices.
				Be right, come over and shop at Gamextreme!</p>
			</div>
			<div class="col_1_of_3 span_1_of_3">
				<h3>Our stores are conveniently located in:</h3>
				<div class="history-desc">
					<p class="history">3/F Digital Exchange, Glorietta 3, Makati City</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">2/F Bridgeway Link, Greenhills Shopping Center, San Juan</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">3/F VMall, Greenhills Shopping Center, San Juan</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">L14 Lower Level, Shoppesville Plus, Shoppesville, Greenhills Shopping Center, San Juan</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">2/F Pop Culture, Alabang Town Center, Muntinlupa City</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">G/F, Alabang Mall, Alabang Town Center, Muntinlupa City</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">4C-16 168 Shopping Mall, Sta. Elena Street, Binondo, Manila</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">1S01 G/F 168 Shopping Mall, Sta. Elena Street, Binondo, Manila</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">2/F U101 Eastwood Cyber and Fashion Mall, Eastwood City Cyber Park, Bagumbayan, Quezon City</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">WS001 SM Center Pasig, Fontelera Verde, Barangay Ugong, Pasig City</p>
				 	<div class="clear"></div>
				</div>
				<div class="history-desc">
					<p class="history">627 Ronquillo Street, Quiapo, Manila</p>
				 	<div class="clear"></div>
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