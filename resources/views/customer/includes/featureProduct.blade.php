<?php $featuredProducts = App::make("App\Http\Controllers\GlobalController")->featuredProduct(); ?>
@if(count($featuredProducts) != 0)
<div class="content_bottom">
	<div class="heading">
		<h3>Feature Products</h3>
	</div>
	<div class="see">
		<p><a href="{{URL::Route('getAllFeaturedProduct')}}">See all Products</a></p>
	</div>
	<div class="clear"></div>
</div>
<div class="section group">
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
@endif