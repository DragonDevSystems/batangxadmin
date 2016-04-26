<div class="header_top">
	<div class="logo">
		<a href="{{ URL::Route('cusIndex') }}"><img width="217" height="74" src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png" alt="" /></a>
	</div>
	  <div class="cart">
	  	   <p>Welcome to our Online Store! <span>Cart:</span><div id="dd" class="wrapper-dropdown-2"><span id="cartInfo"></span>
	  	   	<ul class="dropdown on-cart-list">
				<div class="col-md-12">
					<div class="form-group">
						<label for="name">qty</label>
						<label for="name">Product</label>
						<label for="name">total</label>
						<button type="button" class="btn btn-danger" style="float: right;height: 30px;width: 30px;text-align: center;">x</button>
					</div>
				</div>
	  	   	<!--<li>2 item(s) lg g4 = P2000 <i class="fa fa-remove"></i></li>-->
			</ul></div></p>
	  </div>
	  <script type="text/javascript">
	function DropDown(el) {
		this.dd = el;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				$.get('{{URL::Route('onCartList')}}', function(response)
		   		{
		   			if(response.length != 0)
					{
		   				$('.on-cart-list').empty();
		   				for($x=0 ; $x < response[0].productInfo.length ;$x++)
		   				{
							$('.on-cart-list').append('<div class="col-md-12">\
														<div class="form-group">\
															<label for="name">'+response[0].productInfo[$x].qty+'</label>\
															<label for="name">'+response[0].productInfo[$x].name+'</label>\
															<label for="name">'+response[0].productInfo[$x].price+'</label>\
															<button type="button" onClick="removeItemCart('+response[0].productInfo[$x].cart_id+');" class="btn btn-danger" style="float: right;height: 30px;width: 30px;text-align: center;">x</button>\
														</div>\
													</div>');

		   				}
		   				$('.on-cart-list').append("<li>===========================================</li>")
		   				$('.on-cart-list').append('<li>'+response[0].totalQty+' item(s) - Total: '+response[0].totalPrice+'</li>')

					}
				});
				$(this).toggleClass('active');
				event.stopPropagation();
			});	
		}
	}

	$(function() {

		var dd = new DropDown( $('#dd') );

		$(document).click(function() {
			$('.wrapper-dropdown-2').removeClass('active');
		});

	});

	function removeItemCart(cid)
	{
		var _token = "{{ csrf_token() }}";
		$.post('{{URL::Route('removeOnCart')}}', { _token: _token , cid : cid }, function(response)
   		{
   			promptMsg(response.status,response.message);
   			onCartproduct();
   		});
	}
	function onCartproduct()
	{
		$.get('{{URL::Route('productOnCart')}}', function(response)
   		{
   			if(response.length != 0)
			{
   				$("#cartInfo").empty();
				$("#cartInfo").html(response[0].totalQty+' item(s) - '+response[0].totalPrice);
			}
		});
	}
	onCartproduct();
	window.setInterval(function () {onCartproduct()}, 5000);

</script>
<div class="clear"></div>
</div>