<div class="header_top">
	<div class="logo">
		<a href="index.html"><img src="{{env('FILE_PATH_CUSTOM')}}img/logo.png" alt="" /></a>
	</div>
	  <div class="cart">
	  	   <p>Welcome to our Online Store! <span>Cart:</span><div id="dd" class="wrapper-dropdown-2"> 0 item(s) - $0.00
	  	   	<ul class="dropdown">
					<li>you have no items in your Shopping cart</li>
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
				$(this).toggleClass('active');
				event.stopPropagation();
			});	
		}
	}

	$(function() {

		var dd = new DropDown( $('#dd') );

		$(document).click(function() {
			// all dropdowns
			$('.wrapper-dropdown-2').removeClass('active');
		});

	});

</script>
<div class="clear"></div>
</div>