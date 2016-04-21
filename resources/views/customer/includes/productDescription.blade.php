	<div class="product_desc">	
		<div id="horizontalTab">
		<ul class="resp-tabs-list">
			<li>Product Details</li>
			<li>product Specs</li>
			<div class="clear"></div>
		</ul>
		<div class="resp-tabs-container">
			<div class="product-desc">
				<p>{{$response[0]['productInfo']['description']}}</p>
			</div>

			<div class="product-tags">
				<table class="table table-striped">
					<thead>
						<tr>
						<th style="text-align: center;">{{$response[0]['productInfo']['name']}}'s Device Specification</th>
						</tr>
					</thead>
					<tbody>
						@for($x = 0 ; $x < count($response[0]['pro_specs']) ; $x++)
							<tr>
								<td>{{$response[0]['pro_specs'][$x]['specs']}}</td>
							</tr>
						@endfor
					</tbody>
				</table>
			</div>
		</div>
		</div>
	</div>
	 <script type="text/javascript">
		$(document).ready(function () {
			$('#horizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion           
				width: 'auto', //auto or any width like 600px
				fit: true   // 100% fit in a container
			});
		});
	</script>