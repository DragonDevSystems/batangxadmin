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
				@if($userInfo['isVerified'] == 0)
				<h3> Your email is not yet verified. To verify click here..<a href="javascript:void(0)" onClick="resendEmailverificationLink();">Resend email.</a></h3>
				@endif	
		          <div class="form-group has-feedback has-error"></div>
		          <label>Username:</label>
		          <div class="form-group has-feedback">
		            <input type="text" value="{{$userInfo['un']}}" class="form-control" id="myUsername" name="myUsername" placeholder="Username" required>
		            <!--<span class="glyphicon glyphicon-star form-control-feedback"></span>-->
		          </div>
		          <label>Email:</label>
		          <div class="form-group has-feedback">
		            <input type="text" value="{{$userInfo['email']}}" class="form-control" id="myEmail" name="myEmail" placeholder="Email" required>
		            <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
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
		              <input type="text" value="{{$userInfo['mobile']}}"class="form-control" placeholder="Mobile or Phone Number" name="myMobile" id="myMobile" data-inputmask='"mask": "(999) 999-9999"' data-mask required>
		            </div>
		          </div>
		          <div class="form-group">
		          		<label>Address:</label>
		              	<textarea style="resize: none;" class="form-control" rows="5" placeholder="Enter Address..." name="myAddress" id="myAddress" required>{{$userInfo['address']}}</textarea>
		          </div>
		          <div class="row">
		            <div class="col-md-6">
		              <a href="javascript:void(0);" id="changePass" class="changePass">Change password</a><br>
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
						<div class="contact-form" style="width: 50px:display:block">
							<h2>Invoice List</h2>
							<div class="row">
								<div class="">
									<table  id="invoice_list" class="table table-striped invoice-list">
										<thead>
											<tr>
												<th>Invoice No.</th>
												<th>Date</th>
												<th>Customer Name</th>
												<th>Status</th>
												<th>Action</th>
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
	//var table = $('#invoice_list').DataTable();
	$('#invoice_list').dataTable( {
	  "columnDefs": [
	    { "width": "10%", "targets": 0 }
	  ]
	} );
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
				/*for(var i = 0; i < response.dataInfo.length ; i++)
				{
					
					$('.invoice-list tbody').append('<tr>\
														<td><a href="'+response.dataInfo[i].invoice_link+'" target="_blank">'+response.dataInfo[i].invoice_num+'</a></td>\
														<td>'+response.dataInfo[i].invoice_date+'</td>\
														<td>'+response.dataInfo[i].cus_name+'</td>\
														<td>'+response.dataInfo[i].status+'</td>\
													</tr>');
				}*/
				$('#invoice_list').DataTable().clear();
				for (var i = 0; i < response.dataInfo.length ; i++) 
	        	{
					$('#invoice_list').DataTable().row.add(['<a href="'+response.dataInfo[i].invoice_link+'" target="_blank">'+response.dataInfo[i].invoice_num+'</a>', 
	                                                    ''+response.dataInfo[i].invoice_date+'', 
	                                                    ''+response.dataInfo[i].cus_name+'',
	                                                    ''+response.dataInfo[i].status+'',
	                                                    '<button style="'+response.dataInfo[i].status_type+'" onclick="cancelReservation('+response.dataInfo[i].user_id+','+response.dataInfo[i].inv_id+')" type="button" class="myButton btn btn-danger btn-sm">Cancel Reservation</button>',
	                                                    ]).draw();
				}
				
			}
	 	});
	}

	function resendEmailverificationLink()
	{
		$_token = "{{ csrf_token() }}";
		$.post('{{URL::Route('resendEmailverificationLink')}}',{ _token: $_token},function(response)
	 	{
	 		if(response.length != 0)
			{
				promptMsg(response.status,response.message);				
			}
	 	});
	}

	$(document).on("click","#changePass",function(){
		$('body').append('<div class="modal proceed_pass_modal" data-keyboard="false" data-backdrop="static">\
					            <div class="modal-dialog">\
					              <div class="modal-content">\
					                <div class="modal-header">\
					                  <h4 class="modal-title">\
						                    </span>Enter your password to proceed.\
						                 </h4>\
					                </div>\
					                <div class="modal-body">\
					                	<div class="input-group" style="margin:auto;width:250px">\
									      <input type="password" class="form-control" placeholder="Enter password...">\
									      <span class="input-group-btn">\
									        <button class="btn btn-default" type="button">Confirm</button>\
									      </span>\
									    </div>\
									    <div class="form-group for_error" style="margin:auto;width:250px">\
											<label></label>\
										</div>\
					                </div>\
					                 <div class="modal-footer">\
								        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
								      </div>\
					              </div>\
					            </div>\
					          </div>');
		$(".proceed_pass_modal").modal("show");
		$(document).on("click",".proceed_pass_modal .modal-body button",function(){
			var pass = $('.proceed_pass_modal').find('input').val();
			var _token = "{{ csrf_token() }}";
			$.post('{{URL::Route('checkUserPass')}}',{ _token : _token , pass : pass },function(response)
	 		{
	 			if(response.status == "success"){
	 				$(".proceed_pass_modal").modal("hide");
	 				$('.for_error').find('label').html('<i class="fa fa-check"></i> Correct password. You may proceed.');
	 				$('body').append('<div class="modal change_pass_modal" data-keyboard="false" data-backdrop="static">\
					            <div class="modal-dialog">\
					              <div class="modal-content">\
					                <div class="modal-header">\
					                  <h4 class="modal-title">\
						                    <span class="fa fa-lock">\
						                    </span>Change Password\
						                 </h4>\
					                </div>\
					                <div class="modal-body">\
					                	<form method="post" action="{{URL::Route('changeUserPass')}}">\
						                	<div class="form-group">\
							                	<label>Enter New Password</label>\
							                  <div class="form-group has-feedback">\
									            <input type="password"  class="form-control" placeholder="Enter new password" name="myPassword" id="myPassword" required>\
									            <!--<span class="glyphicon glyphicon-lock form-control-feedback"></span>-->\
									          </div>\
								         	</div>\
								         	<div class="form-group">\
								          		<label>Enter Re-type Password</label>\
												<div class="form-group has-feedback">\
												<input type="password"  class="form-control" placeholder="Re-type Password" name="my_repassword" id="my_repassword" required>\
												<!--<span class="glyphicon glyphicon-lock form-control-feedback"></span>-->\
												</div>\
											</div>\
											<div class="form-group for_error">\
											<label></label>\
											</div>\
											<input type="hidden" value="{{ csrf_token() }}" name="_token">\
					    					<button type="submit" id="submitChangePass" style="display:none"></button>\
					    				</form>\
					                </div>\
					                 <div class="modal-footer">\
								        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
								        <button type="button" class="btn btn-primary saveChangePassword">Save</button>\
								      </div>\
					              </div>\
					            </div>\
					          </div>');
					$(".change_pass_modal").modal("show");
					$(document).on("keyup","#myPassword",function(){
			if($('#my_repassword').hasClass('notEmpty')){
				$enPass = $(this).val();
				$rePass = $('#my_repassword').val();
				if($rePass != $enPass){
					$('.for_error').find('label').html('<i class="fa fa-times"></i> Password did not match.');
      				$('.for_error').removeClass('has-success').addClass('has-warning');
				}
				else{
					$('.for_error').find('label').html('<i class="fa fa-check"></i> Password match.');
      				$('.for_error').addClass('has-success').removeClass('has-warning');
				}
			}
		});
		$(document).on("keyup","#my_repassword",function(){
			if($(this).length == 0){
				$(this).removeClass('notEmpty');
			}
			$(this).addClass('notEmpty');
			$rePass = $(this).val();
			$enPass = $('#myPassword').val();
			if($rePass != $enPass){
				$('.for_error').find('label').html('<i class="fa fa-times"></i> Password did not match.');
      			$('.for_error').removeClass('has-success').addClass('has-warning');
			}
			else{
				$('.for_error').find('label').html('<i class="fa fa-check"></i> Password match.');
      			$('.for_error').addClass('has-success').removeClass('has-warning');
			}
		});
		$(document).on("click",".saveChangePassword",function(){
			if(!$('.for_error').hasClass('has-warning')){
				var status = confirm('Are you sure?');
				if(status == true){

					$('.change_pass_modal').find('#submitChangePass').click();
				}
				
			}
			else{
				alert('password does not match.')
			}
		});
	 			}
	 			else{
	 				$('.for_error').find('label').html('<i class="fa fa-times"></i>You input wrong password. Please try again.');
	 			}
	 		});
		});
	});
	$(document).on("hidden.bs.modal",".change_pass_modal",function(){
	    	$('body').addClass('remove_body_padding');
			$(this).remove();
	});
	$(document).on("hidden.bs.modal",".proceed_pass_modal",function(){
	    	$('body').addClass('remove_body_padding');
			$(this).remove();
	});
	function cancelReservation(cus_id,inv_id)
	{
	  var type = 3;
	  // promptConfirmation("Are you sure you want to cancel this invoice?");
	     // $('#btnYes').click(function() {
	        var _token = "{{ csrf_token() }}";
	        var status = confirm('Are you sure?');
	        if(status == true){
	        	 $.post('{{URL::Route('cancelledReservation',[0,0,0])}}',{ _token: _token , cus_id: cus_id, inv_id: inv_id , type: type},function(response)
		      	{
			        if(response.length != 0)
			        {
			          //$('#mdl_invoice').modal('hide');
			          promptMsg(response.status,response.message)
			          setTimeout(function(){ window.location.reload(); }, 3000);
			        }
			    });
	        }
	       
	    //});
	    return false;
	}
</script>
@endsection