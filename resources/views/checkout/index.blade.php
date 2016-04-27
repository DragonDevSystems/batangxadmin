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
					<div class="contact-form">
						<!-- title row -->
						<div class="row">
							<div class="col-xs-12">
								<h2 class="page-header">
									<img width="150" height="51" src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png" alt="" />
									<small class="pull-right">Date: 2/10/2014</small>
								</h2>
							</div>
							<!-- /.col -->
						</div>
						<!-- info row -->
						<div class="row invoice-info">
							<div class="col-sm-4 invoice-col">
								From
								<address>
								<strong>GameXtreme</strong><br>
								Alabang Town Center,<br>
								Muntinlupa City, Metro Manila<br>
								Phone: (02) 842-2782<br>
								Email: allenjamesxxiv@gmail.com
								</address>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 invoice-col">
								To
								<address>
								<strong>{{$userInfo['fname']}} {{$userInfo['lname']}}</strong><br>
								Phone: {{$userInfo['mobile']}}<br>
								Email: {{$userInfo['email']}}
								</address>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 invoice-col">
								<b>Invoice #007612</b><br>
								<br>
								<b>Order ID:</b> 4F3S8J<br>
								<b>Payment Due:</b> 2/22/2014<br>
								<b>Account:</b> 968-34567
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

						<!-- Table row -->
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
										
										@if($onCartList[0]['totalQty'] != 0)
											@for($x=0 ; $x < count($onCartList[0]['productInfo']) ; $x++)
												<tr>
													<td>{{$onCartList[0]['productInfo'][$x]['qty']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['name']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['unit_price']}}</td>
													<td>{{$onCartList[0]['productInfo'][$x]['price']}}</td>
												</tr>
											@endfor
										@endif

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
						</div>
						<!-- /.col -->
						<div class="col-xs-6">
						<p class="lead">Amount Due 2/22/2014</p>

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
						<a href="{{URL::Route('getCheckOutPrint')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
						<button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
						</button>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('customer.includes.footer')

@endsection