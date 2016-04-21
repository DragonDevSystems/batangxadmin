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
			<div class="section group">
				@if(!empty($response))
					@for($x = 0 ; $x < count($response) ; $x++)
						<div class="grid_1_of_4 images_1_of_4">
							<a href="{{ URL::Route('productPreview',[$response[$x]['productInfo']['id'],$response[$x]['productInfo']['name']]) }}"><img width="212" height="212" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$response[$x]['pro_img']}}" alt="" /></a>
							 <h2>{{$response[$x]['productInfo']['name']}}</h2>
							<div class="price-details">
						       <div class="price-number">
									<p><span class="rupees">$679.87</span></p>
							    </div>
					       		<div class="add-cart">								
									<h4><a href="{{ URL::Route('productPreview',[$response[$x]['productInfo']['id'],$response[$x]['productInfo']['name']]) }}">Add to Cart</a></h4>
							     </div>
							 <div class="clear"></div>
							</div>				     
						</div>
					@endfor
				@else
					<div class="heading">
						<h3>No Products yet in this category</h3>
					</div>
				@endif
				

			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="main">
		<div class="content">
			@include('customer.includes.newProduct')
			@include('customer.includes.featureProduct')
		</div>
	</div>
</div>
	@include('customer.includes.footer')
@endsection