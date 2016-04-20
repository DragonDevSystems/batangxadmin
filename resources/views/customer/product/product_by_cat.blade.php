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
				<div class="slider">
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="{{env('FILE_PATH_CUSTOM')}}img/feature-pic1.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<div class="price-details">
							<div class="price-number">
								<p><span class="rupees">$620.87</span></p>
							</div>
							<div class="add-cart">								
								<h4><a href="preview.html">Add to Cart</a></h4>
							</div>
							<div class="clear"></div>
						</div>
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