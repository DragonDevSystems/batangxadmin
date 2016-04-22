<div class="product-details">				
					<div class="grid images_3_of_2">
						<div id="container">
						   <div id="products_example">
							   <div id="products">
								<div class="slides_container">
									@for($x = 0 ; $x < count($response[0]['pro_img']) ; $x++)
									<a href="#" target="_blank"><img src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$response[0]['pro_img'][$x]['thumbnail_img']}}" alt=" " /></a>
									@endfor
								</div>
								<ul class="pagination">
									@for($x = 0 ; $x < count($response[0]['pro_img']) ; $x++)
										<li><a href="#"><img src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$response[0]['pro_img'][$x]['thumbnail_img']}}" alt=" " /></a></li>
									@endfor
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="desc span_3_of_2">
					<h2>{{$response[0]['productInfo']['name']}}</h2>
					<p>{{$response[0]['productInfo']['description']}}</p>					
					<div class="price">
						<p>Price: <span>{{$response[0]['productPrice']}}</span></p>
					</div>
					<div class="available">
						<p>Available Options :</p>
					<ul>
						<li>Quality:<select>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
						</select></li>
					</ul>
					</div>
				<div class="share-desc">
					<div class="button"><span><a href="#">Add to Cart</a></span></div>					
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		  </div>