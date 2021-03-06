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
			<p>Status:  <font color='{{($response[0]["pro_qty"] == "Available") ? "green" : "red"}}' size="4">{{$response[0]['pro_qty']}}</font></p>
			<p>Available Item:  <font color='{{($response[0]["item_remain"] != 0) ? "green" : "red"}}' size="3">{{$response[0]['item_remain']}}</font></p>
			<ul>
				<li>Quantity:<select id="qty">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select></li>
			</ul>
		</div>
		<div class="share-desc">
			<div class="button"><span><a href="javascript:void(0)" onClick="addToCart({{$response[0]['productInfo']['id']}});">Add to Cart</a></span></div>					
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	function addToCart(pid)
	{
		$availability = "{{$response[0]["pro_qty"]}}";
		var _token = "{{ csrf_token() }}";
		var qty = $('#qty').val();
		if($availability == "Available")
		{
			$.post('{{URL::Route('addToCart',(!empty(Auth::User()['id'])) ? Auth::User()['id'] : 0 )}}',{ _token: _token ,  prod_id: pid ,  qty: qty},function(response)
   		 	{
   		 		if(response.length != 0)
				{
					promptMsg(response.status,response.message)
				}
   		 	});
		}
		else
		{
			promptMsg("fail","Out of stock.")
		}
	}
</script>