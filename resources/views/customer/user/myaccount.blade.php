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
			<div class="row">
				<div class="col-md-6">
					<div class="section group">
						<div class="col span_2_of_3">
							<div class="contact-form">
							  	<h2>My Account</h2>
							    <form method="post" action="">
							    	<div>
								    	<span><label>Lastname</label></span>
								    	<span><input name="lastname" value="{{$userInfo['lname']}}" type="text" class="textbox" required></span>
								    </div>
								    <div>
								    	<span><label>Firstname</label></span>
								    	<span><input name="firstname" value="{{$userInfo['fname']}}" type="text" class="textbox" required></span>
								    </div>
								    <div>
								    	<span><label>E-mail</label></span>
								    	<span><input name="email" value="{{$userInfo['email']}}" type="email" class="textbox" required></span>
								    </div>
								  <input type="hidden" value="{{ csrf_token() }}" name="_token">
							    </form>
							</div>
		  				</div>
		  			</div>
				</div>
				<div class="col-md-6">
					<div class="section group">
						<div class="col span_2_of_3">
							<div class="contact-form">
								<div class="row">
									<div class="col-xs-12 table-responsive">
										<table class="table table-striped invoice-list">
											<thead>
												<tr>
													<th>Invoice No.</th>
													<th>Date</th>
													<th>Customer Name</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('customer.includes.footer')
<script type="text/javascript">
	getInvoiceList();
	function getInvoiceList()
	{
		$.get('{{URL::Route('getInvoiceList',Auth::User()['id'])}}',function(response)
	 	{
	 		if(response.length != 0)
			{
				for(var i = 0; i < response.dataInfo.length ; i++)
				{
					$('.invoice-list tbody').append('<tr>\
														<td><a href="'+response.dataInfo[i].invoice_link+'" target="_blank">'+response.dataInfo[i].invoice_num+'</a></td>\
														<td>'+response.dataInfo[i].invoice_date+'</td>\
														<td>'+response.dataInfo[i].cus_name+'</td>\
														<td>'+response.dataInfo[i].status+'</td>\
													</tr>');
				}
				
			}
	 	});
	}
</script>
@endsection