<div class="header_top">
	<div class="logo">
		<a href="{{ URL::Route('cusIndex') }}"><img width="217" height="74" src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png" alt="" /></a>
	</div>
	  <div class="cart">
	  	   <p>Welcome to our Online Store! <span>Cart:</span><div id="dd" class="wrapper-dropdown-2"><span id="cartInfo"></span>
	  	   	<ul class="dropdown on-cart-list">
	  	   	<li>2 item(s) lg g4 = P2000</li>
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
				/*$('.on-cart-list').empty();
				$('.on-cart-list').append("<li>asdasdas</li>");*/
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