@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
  	<script src="{{env('FILE_PATH_CUSTOM')}}js/easyResponsiveTabs.js" type="text/javascript"></script>
	<link href="{{env('FILE_PATH_CUSTOM')}}css/easy-responsive-tabs.css" rel="stylesheet" type="text/css" media="all"/>
	<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}css/global.css">
	<script src="{{env('FILE_PATH_CUSTOM')}}js/slides.min.jquery.js"></script>
	<script>
	$(function(){
		$('#products').slides({
		preload: true,
		preloadImage: '{{env('FILE_PATH_CUSTOM')}}img/loading.gif',
		effect: 'slide, fade',
		crossfade: true,
		slideSpeed: 350,
		fadeSpeed: 500,
		generateNextPrev: true,
		generatePagination: false
		});
	});
	</script>
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
			    	<div class="content_top">
    		<div class="back-links">
    		<p><a href="{{ URL::Route('cusIndex') }}">Home</a> >>>> <a href="#">Electronics</a></p>
    	    </div>
    		<div class="clear"></div>
    	</div>
    	<div class="section group">
			<div class="cont-desc span_1_of_2">
				@include('customer.includes.productDetails')
				@include('customer.includes.productDescription')
						
				@include('customer.includes.relatedProduct')
			</div>
			@include('customer.includes.categories2')
 		</div>
 	</div>
		</div>
	</div>
</div>
	@include('customer.includes.footer')
	<script type="text/javascript">
		$(document).ready(function() {			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>
    <a href="#" id="toTop"><span id="toTopHover"> </span></a>
@endsection