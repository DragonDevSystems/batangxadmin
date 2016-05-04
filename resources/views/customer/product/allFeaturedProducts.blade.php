@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
		@include('customer.includes.mainMenu')
		<div class="header_slide">
			@include('customer.includes.categories')
			<div class="header_bottom_right">
			<h2 style="text-align: center">All Featured Products</h2>
			<?php $featuredProducts = App::make("App\Http\Controllers\GlobalController")->featuredProduct(); ?>
				@foreach($featuredProducts as $featuredProduct)
					<div class="grid_1_of_4 images_1_of_4">
						 <a href="{{ URL::Route('productPreview',[$featuredProduct['productInfo']['id'],$featuredProduct['productInfo']['name']]) }}"><img width="212" height="212" style="display:block; margin:auto;" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$featuredProduct['pro_img']['thumbnail_img']}}" alt="" /></a>
						 <h2>{{$featuredProduct['productInfo']['name']}}</h2>
						<div class="price-details">
					       <div class="price-number">
								<p><span class="rupees">{{$featuredProduct['productPrice']}}</span></p>
						    </div>
						       		<div class="add-cart">								
										<h4><a href="{{ URL::Route('productPreview',[$featuredProduct['productInfo']['id'],$featuredProduct['productInfo']['name']]) }}">Add to Cart</a></h4>
								     </div>
								 <div class="clear"></div>
						</div>
						 
					</div>
				@endforeach
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
	@include('customer.includes.footer')
@endsection