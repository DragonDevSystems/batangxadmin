   <?php $topNewProduct = App::make("App\Http\Controllers\GlobalController")->relatedProduct(6,$response[0]['cat_id']); ?>
   <div class="content_bottom">
    		<div class="heading">
    		<h3>Related Products</h3>
    		</div>
    		<div class="clear"></div>
    	</div>
   <div class="section group">
		@foreach($topNewProduct as $topNewProducti)
		<div class="grid_1_of_4 images_1_of_4">
			 <a href="{{ URL::Route('productPreview',[$topNewProducti['productInfo']['id'],$topNewProducti['productInfo']['name']]) }}"><img width="212" height="212" style="display:block; margin:auto;" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$topNewProducti['pro_img']['thumbnail_img']}}" alt="" /></a>
			 <h2>{{$topNewProducti['productInfo']['name']}}</h2>
			<div class="price-details">
		       <div class="price-number">
					<p><span class="rupees">{{$topNewProducti['productPrice']}}</span></p>
			    </div>
			       		<div class="add-cart">								
							<h4><a href="{{ URL::Route('productPreview',[$topNewProducti['productInfo']['id'],$topNewProducti['productInfo']['name']]) }}">Add to Cart</a></h4>
					     </div>
					 <div class="clear"></div>
			</div>
		</div>
	@endforeach
	</div>