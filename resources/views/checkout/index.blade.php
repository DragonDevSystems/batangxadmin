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
	</div>
	<div class="main">
		<div class="content">
			<div class="section group">
				<div class="col span_2_of_3">
				@if($onCartList[0]['totalQty'] != 0)
					<div class="contact-form">
						<div class="row">
							<div class="col-xs-12 table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Qty</th>
											<th>Product</th>
											<th>Unit Price</th>
											<th>Subtotal</th>
										</tr>
									</thead>
									<tbody>
										
										
											@for($x=0 ; $x < count($onCartList[0]['productInfo']) ; $x++)
												<tr>
													<td>{{$onCartList[0]['productInfo'][$x]['qty']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['name']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['unit_price']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['price']}}</td>
													<td><button type="button" class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i></button></td>
												</tr>
											@endfor
										

									</tbody>
								</table>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

						<div class="row">
							<!-- accepted payments column -->
							<div class="col-xs-6">
							<p class="lead">Payment Methods:</p>
							<img src="{{env('FILE_PATH_CUSTOM')}}img/paypal2.png" alt="Paypal">
							<img width="51" height="32" src="{{env('FILE_PATH_CUSTOM')}}img/cashpickup.jpg" alt="Cash Pick Up">
							</div>
							<!-- /.col -->
							<div class="col-xs-6">
								<div class="table-responsive">
									<table class="table">
										<tr>
											<th>No. Items:</th>
											<td>{{$onCartList[0]['totalQty']}}</td>
										</tr>
										<tr>
											<th style="width:50%">Subtotal:</th>
											<td>{{$onCartList[0]['totalPrice']}}</td>
										</tr>
										<tr>
										</tr>
									</table>
								</div>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

						<!-- this row will not appear when printing -->
						<div class="row no-print">
							<div class="col-xs-12">
								<a href="javascript:void(0);" onClick="cashOnDelivery();" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Cash on Pick-up
								</a>
								<a href="{{URL::Route('getCheckout')}}" class="btn pull-right btn-success"><!--<img height="49" width="200" src="{{env('FILE_PATH_CUSTOM')}}img/checkoutpaypal.png" alt="Paypal">--><i class="fa fa-cc-paypal"></i>Check out with paypal</a>
							</div>
						</div>
					</div>
					@else
						<div class="contact-form">
							<p><font color='red' size="4">No item ready for check out!.</font></p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@include('customer.includes.footer')
<script type="text/javascript">
	function cashOnDelivery()
	{
		var _token = "{{ csrf_token() }}";
		$.post('{{URL::Route('cashOnDelivery')}}',{ _token: _token},function(response)
	 	{
	 		if(response.length != 0)
			{
				promptMsg(response.status,response.message)
			}
	 	});
	}
</script>
@endsection