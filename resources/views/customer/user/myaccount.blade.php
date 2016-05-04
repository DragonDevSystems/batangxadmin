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
			<form method="post" action="{{URL::Route('updateUserInfo')}}">
				<div class="login-box-body" style="width:50%;margin: auto;">
		          <div class="form-group has-feedback has-error"></div>
		          <label>Username:</label>
		          <div class="form-group has-feedback">
		            <input type="text" value="{{$userInfo['un']}}" class="form-control" id="myUsername" name="myUsername" placeholder="Username" required>
		            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		          </div>
		          <label>Email:</label>
		          <div class="form-group has-feedback">
		            <input type="text" value="{{$userInfo['email']}}" class="form-control" id="myEmail" name="myEmail" placeholder="Email" required>
		            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		          </div>
		          <div class="row">
		            <div class="col-md-6">
		              <div class="form-group">
		              	<label>Firstname:</label>
		                <input type="text" value="{{$userInfo['fname']}}" class="form-control" placeholder="Firstname" name="myFirstname" id="myFirstname" required>
		              </div>
		              <div class="form-group">
		              <label>Gender:</label>
		                <select type="text" class="form-control select2" placeholder="Gender" name="myGender" id="myGender" required>
		                      <option value="1">Male</option>
		                      <option value="2">Female</option>
		                </select>
		              </div>
		            </div>
		            <div class="col-md-6">
		              <div class="form-group">
		              <label for="myLastname">Lastname:</label>
		                <input type="text" value="{{$userInfo['lname']}}" class="form-control" placeholder="Lastname" name="myLastname" id="myLastname" required>
		              </div>
		              <div class="form-group">
		              		<label>Date of birth:</label>
		                	<input type="text" value="{{$userInfo['dob']}}" class="form-control" placeholder="Date of Birth" name="myDob" id="myDob" required>
		              </div>
		            </div>
		          </div>
		          <div class="form-group">
		          	<label>Mobile No:</label>
		            <div class="input-group">
		              <div class="input-group-addon">
		                <i class="fa fa-phone"></i>
		              </div>
		              <input type="text" value="{{$userInfo['mobile']}}"class="form-control" placeholder="Mobile or Phone Number" name="myMobile" id="myMobile" data-inputmask='"mask": "(99) 9999-99999"' data-mask required>
		            </div>
		          </div>
		          <div class="form-group">
		          		<label>Address:</label>
		              	<textarea style="resize: none;" class="form-control" rows="5" placeholder="Enter Address..." name="myAddress" id="myAddress" required>{{$userInfo['address']}}</textarea>
		          </div>
		          <div class="row">
		            <div class="col-md-6">
		              <a href="javascript:void(0);" class="btn_reset">Change password</a><br>
		            </div>
		            <!-- /.col -->
		            <div class="col-md-6">
		              <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnMyUpdate">Update</button>
		            </div>
		            <!-- /.col -->
		          </div>
		        </div>
		        <input type="hidden" value="{{ csrf_token() }}" name="_token">
		    </form>
			<div class="row" style="padding-top: 50px">
				<div class="section group">
					<div class="">
						<div class="contact-form">
							<h2>Invoice List</h2>
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
@include('customer.includes.footer')
<script type="text/javascript">
	$(function () {
      $('#myDob').datepicker();
      $("[data-mask]").inputmask();
      //$(".select2").select2();
    });
	$('#gender')
      .val("{{$userInfo['gender']}}") //select option of select2
      .trigger("change"); 
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