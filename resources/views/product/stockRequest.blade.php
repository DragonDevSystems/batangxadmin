@extends('layouts.master')
@section('addHead')
  <title>Stock Request</title>
@endsection

@section('content')
<div class="wrapper">
@include('includes.header')
  @include('includes.mainNav')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Stock Request
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Stock Request</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div-entry" class="box box-danger">
		</div>

		<!-- product list -->
		<div class="box box-danger">
            <div class="box-header">
            	<h3 class="box-title">Stock Request</h3>
            	<div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" data-placeid="" onClick="setNewEntry();">
                    <i class="fa fa-plus"></i>
                    Stock Request
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="delivery_list" class="table table-bordered table-hover">
                <thead>
                <?php 
                    $headers = Schema::getColumnListing('prod_delivery_receipt');
                    $include = ["id","receipt_num","created_at"]; 
                ?>
                <tr>
                  <th>Request Number</th>
                  <th>Status</th>
                  <th>Date Created</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
        </div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
	$(document).ready(function() {
		defaultDisplay();
		var table = $('#delivery_list').DataTable( {
		        "order": [[ 0, "desc" ]]
		    } );
		$('#delivery_list tbody').on( 'click', 'tr', function () {
			if ( $(this).hasClass('active') ) {
	            $(this).removeClass('active');
	        }
	        else {
	            table.$('tr.active').removeClass('active');
	            $(this).addClass('active');
	        }
	        var data = table.row( this ).data();
	        getReceiptProduct(data[0]);
		});
		getReceiptList();

	});
	function getReceiptProduct(id)
	{
			
		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('getRequestProduct')}}',{id : id}, function(data)
		{
			console.log(data)
	      		$('#div-entry').empty();
				$('#div-entry').append('<div class="box-header with-border">\
			          <h3 class="box-title">Stock Request Information</h3>\
			          <div class="box-tools pull-right">\
						<button id="editDelivery" class="btn btn-info btn-sm " type="button">\
							<i class="fa fa-edit"></i>\
							Edit\
						</button>\
						<button id="canceleditDelivery" class="btn btn-warning btn-sm " type="button" style="display:none">\
							<i class="fa fa-edit"></i>\
							Cancel\
						</button>\
			            <button type="button" class="btn btn-danger btn-sm"  onClick="defaultDisplay()"><i class="fa fa-undo" aria-hidden="true"></i></button>\
			          </div>\
			        </div>');

				$('#div-entry').append('<div class="box-body formAddProduct">\
														<div class="row">\
															<div class="col-md-6">\
																<div class="form-group">\
																  <label for="receipt_num">Request Number:</label>\
																  <input type="text" class="form-control" id="receipt_num" name="receipt_num" value="" placeholder="" disabled>\
																  <input type="hidden" class="form-control" id="type" name="type" value="edit">\
																</div>\
															</div>\
															<div class="col-md-6">\
																<div class="row">\
																	<div class="col-md-7">\
																		<div class="form-group">\
															                <label for="product">Choose Product</label>\
															                <select id="product" name="product" placeholder="Select a product." class="form-control select2" style="width: 100%;" required disabled>\
															                @foreach($allProduct as $product)\
															                <option value="{{$product['id']}}">{{$product['name']}}</option>\
															                @endforeach\
															                </select>\
															            </div>\
															        </div>\
															        <div class="col-md-5">\
																		<div class="form-group ">\
																			<label for="qty">QTY</label>\
																		  	<div class="input-group">\
																			    <input maxlength="2" type="text" id="qty" name="qty" placeholder="add quantity" class="form-control" aria-label="..." disabled>\
																				<div class="input-group-btn">\
																				<button type="button" class="btn btn-default plus new" data-toggle="tooltip" title="Add product" disabled>\
																					<i class="fa fa-plus" aria-hidden="true"></i>\
																				</button>\
																				</div>\
																			</div>\
																		</div>\
															        </div>\
													            </div>\
															</div>\
														</div>\
														<div class="box box-default">\
															<div class="box-header">\
															<h3 class="box-title">Product List</h3>\
															<div class="box-tools pull-right">\
																<button id="remove_product" class="btn btn-danger btn-sm " type="button" disabled>\
																	<i class="fa fa-trash"></i>\
																	Remove\
																</button>\
															</div>\
													        </div>\
															<div class="box-body">\
												              	<table id="qty_list" class="table table-bordered table-hover">\
												                <thead>\
												                <tr>\
												                  <th>Product Id</th>\
												                  <th>Product Name</th>\
												                  <th>QTY</th>\
												                </tr>\
												                </thead>\
												                <tbody>\
												                </tbody>\
												              </table>\
												            </div>\
												        </div>\
												        <div class="form-group">\
										                  <label for="remarks">Status</label>\
										                  <input type="text" class="form-control" id="remarks" name="remarks" value="" placeholder="" disabled>\
										                </div>\
										                <a href="#" target="_blank" class="btn btn-default print"><i class="fa fa-print"></i> Print</a>\
										                <a href="javascript:void(0)" class="btn btn-default confirmReceive" data-id="'+id+'"><i class="fa fa-check"></i>Receive</a>\
							           				</div>\
												');
				var table2 = $('#qty_list').DataTable();
		        $('#qty_list').DataTable().clear().draw();
		        for (var i = 0; i < data.length; i++) 
		        {
		        	if(i == 0){
		        		$('#receipt_num').val(data[i].receipt);
		        		$('#remarks').val(data[i].remarks);
		        		$(".print").attr("href",data[i].url);
		        		if(data[i].remarks == "Received"){
		        			$('.confirmReceive').attr('disabled', true);
		        			$('#editDelivery').attr('disabled', true);
		        		}
		        	}
		        	if(data.length != 0){
		        		$('#qty_list').DataTable().row.add([''+data[i].id+'', 
                                        ''+data[i].name+'', 
                                        ''+data[i].qty+'', 
                                        ]).draw();
		        	}
		        	
		        }

				$('#qty_list tbody').on( 'click', 'tr', function () {
	      
			        if ( $(this).hasClass('active') ) {
			          $(this).removeClass('active');
			          $('#remove_product').prop("disabled", true);
			        }
			        else{
			          table2.$('tr.active').removeClass('active');
			          $(this).addClass('active');
			          $('#remove_product').prop("disabled", false);
			        }
			        $(window).scrollTop($('#div-entry').offset().top);
			    } );
		        $("#product").select2({
					//minimumResultsForSearch: -1,
					placeholder: "Select a product.",
	   				//allowClear: false
				}).select2("val", null);

				 $(document).on("click","#remove_product",function(){
		     		promptConfirmation("Are ou sure you want to remove this record ?");
			     	$("#btnYes").click(function(){
					    $.map(table2.rows('.active').data(), function (item) {
				           	var product =  item[0];
				           	var name =  item[1];
				           	var qty =  item[2];
				           	var type = $('#type').val();
				           	var receipt_num = $('#receipt_num').val();
				            var _token = "{{ csrf_token() }}";

							$.post('{{URL::Route('deleteProductRequest')}}',{ _token: _token ,product: product, name : name , qty : qty ,
							type: type , receipt_num : receipt_num } , function(response)
								{
								if(response.status == "success"){
								  table2.row('.active').remove().draw( false )
								  promptMsg(response.status,response.message);
								}
							});
				        });
					});
			    });

				$(document).on("click","#editDelivery",function(){
					$('#canceleditDelivery').show();
					$('#product,#qty,.plus').removeAttr("disabled")
					$(this).hide();
				});
				$(document).on("click","#canceleditDelivery",function(){
					$('#editDelivery').show();
					$('#product,#qty,.plus').attr('disabled', 'disabled');
					$(this).hide();
				});
		    
		});
	}
	function getReceiptList()
	{
		$.get('{{URL::Route('accountAccessChecker',["add","product"])}}', function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$.get('{{URL::Route('getRequestList',0)}}',{ event: "add"}, function(data)
					{
						if(data.length != 0)
				      	{
					        $('#delivery_list').DataTable().clear().draw();
					        for (var i = 0; i < data.length; i++) 
					        {
					        	$('#delivery_list').DataTable().row.add([''+data[i].id+'',
                                                    ''+data[i].status+'', 
                                                    ''+data[i].date+'', 
                                                    ]).draw();
					        }
					    }
						
					});
				}
			}
		});
	}
	function setNewEntry(id)
	{
		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',["add","product"])}}', function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$('#div-entry').empty();
							$('#div-entry').append('<div class="box-header with-border">\
						          <h3 class="box-title">Add Stock Request</h3>\
						          <div class="box-tools pull-right">\
						            <button type="button" class="btn btn-danger btn-sm"  onClick="defaultDisplay()"><i class="fa fa-undo" aria-hidden="true"></i></button>\
						          </div>\
						        </div>');

							$('#div-entry').append('<div class="box-body formAddProduct">\
															<div class="row">\
																<div class="col-md-5">\
																	<form id="formAddProduct" method="post" role="form">\
																		<div class="row">\
																			<div class="col-md-7">\
																				<div class="form-group">\
																	                <label for="product">Choose Product</label>\
																	                <select id="product" name="product" placeholder="Select a product." class="form-control select2" style="width: 100%;" required>\
																	                @foreach($allProduct as $product)\
																	                <option value="{{$product['id']}}">{{$product['name']}}</option>\
																	                @endforeach\
																	                </select>\
																	            </div>\
																	        </div>\
																	        <div class="col-md-5">\
																				<div class="form-group ">\
																					<label for="qty">QTY</label>\
																				  	<div class="input-group">\
																					    <input maxlength="2" type="text" id="qty" name="qty" placeholder="add quantity" class="form-control" aria-label="...">\
																						<div class="input-group-btn">\
																						<button type="button" class="btn btn-default plus new" data-toggle="tooltip" title="Add product">\
																							<i class="fa fa-plus" aria-hidden="true"></i>\
																						</button>\
																						</div>\
																					</div>\
																				</div>\
																	        </div>\
															            </div>\
																	</form>\
																</div>\
																<div class="col-md-7">\
																	<div class="box box-default">\
																		<div class="box-header">\
																		<h3 class="box-title">Product List</h3>\
																			<div class="box-tools pull-right">\
																            	<button id="remove_product" type="button" class="btn btn-box-tool" disabled><i class="fa fa-remove"></i> Remove</button>\
																          	</div>\
																        </div>\
																		<div class="box-body">\
															              	<table id="qty_list" class="table table-bordered table-hover">\
															                <thead>\
															                <tr>\
															                  <th>Product Id</th>\
															                  <th>Product Name</th>\
															                  <th>QTY</th>\
															                </tr>\
															                </thead>\
															                <tbody>\
															                </tbody>\
															              </table>\
															            </div>\
															        </div>\
																</div>\
															</div>\
															<div class="box-footer">\
																<button  type="button" class="btn btn-primary add_delivery">Add Stock Request</button>\
															</div>\
								           				</div>\
													');
				}
			}
			var table2 = $('#qty_list').DataTable();
			$('#qty_list tbody').on( 'click', 'tr', function () {
      
		        if ( $(this).hasClass('active') ) {
		          $(this).removeClass('active');
		          $('#remove_product').prop("disabled", true);
		        }
		        else{
		          table2.$('tr.active').removeClass('active');
		          $(this).addClass('active');
		          $('#remove_product').prop("disabled", false);
		        }
		    } );
			$(document).on("click",".add_delivery",function(){
				var checkReceipt = $('#receipt_num').closest('.has-warning').length;
				var receipt = $('#receipt_num').val();
				var remarks = $('#remarks').val();
				var _token = "{{ csrf_token() }}";
				var check = $("#qty_list").dataTable().fnSettings().aoData.length;
				$this = $(this);
					if(check != 0){
						promptConfirmation("Are ou sure you want to add this request ?");
			     		$("#btnYes").click(function(){
			     			$.post('{{URL::Route('addRequest')}}',{ _token: _token ,receipt: receipt , remarks : remarks} , function(response)
							{
								if(response.status == "success"){
									defaultDisplay();
									getReceiptList();
									promptMsg("success","Success.");
								}
							});
			     		});
					}
					else{
						promptMsg("fail","Please add product.");
					}
				
					
			});
		    $(document).on("click","#remove_product",function(){
		     	promptConfirmation("Are ou sure you want to remove this record ?");
		     	$("#btnYes").click(function(){
				    $.map(table2.rows('.active').data(), function (item) {
			           	var product =  item[0];
			           	var name =  item[1];
			           	var qty =  item[2];
			            var _token = "{{ csrf_token() }}";

						$.post('{{URL::Route('deleteProductRequest')}}',{ _token: _token ,product: product, name : name , qty : qty } , function(response)
							{
							if(response.status == "success"){
							  table2.row('.active').remove().draw( false )
							  promptMsg(response.status,response.message);
							}
						});
			        });
				});
		    });

			$("#product").select2({
				//minimumResultsForSearch: -1,
				placeholder: "Select a product.",
   				//allowClear: false
			}).select2("val", null);
		});
	}

	$(document).on("keydown","#qty,#receipt_num",function(e){
	        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
   
	$(document).on("click",".plus",function(){
		var receipt_num = $("#receipt_num").val();
		var type = $("#type").val();
		var product = $('#product').val();
		var name = $("#product option:selected").text();
		var qty = $('#qty').val();
		var check = $(this).hasClass('new') ? "new" : "old";
		var _token = "{{ csrf_token() }}";
		$this = $(this);
		$.post('{{URL::Route('addProductRequest')}}',{ _token: _token ,product: product, qty : qty, check : check ,
		receipt_num: receipt_num, type : type} , function(response)
		{
			//console.log(response);
			if(response.status == "success"){
				 $('#qty_list').DataTable().row.add([''+product+'', 
                                                    ''+name+'', 
                                                    ''+qty+'', 
                                                    ]).draw();
				$("#product").select2("val",null);
				$('#qty').val("");
				$this.removeClass('new');
				$('#qty').closest('.form-group').find('label').html('QTY');
          		$('#qty').closest('.form-group').removeClass('has-warning');
          		$('#product').closest('.form-group').find('label').html('Choose Product');
          		$('#product').closest('.form-group').removeClass('has-warning');
          		promptMsg(response.status,response.message);
			}
			else{
				if(!product){
					$('#product').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i>Choose Product - Required');
          			$('#product').closest('.form-group').addClass('has-warning');

				}
				else{
					$('#product').closest('.form-group').find('label').html('Choose Product');
          			$('#product').closest('.form-group').removeClass('has-warning');
				}
				if(!qty)
				{
					$('#qty').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i>QTY - Required');
					$('#qty').closest('.form-group').addClass('has-warning');
					$('#qty').focus();
				}
				else{
					$('#qty').closest('.form-group').find('label').html('QTY');
          			$('#qty').closest('.form-group').removeClass('has-warning');
				}
			}
		});
		
		

	});
	function defaultDisplay()
	{
		$('#editProduct').removeClass('continue_view');
	   	$('#div-entry').append('<div class="overlay">\
			        	<i class="fa fa-spinner fa-spin"></i>\
			        </div>');
	   	$('#div-entry').empty();
		$('#div-entry').append(
				$('<div />' , { 'class' : 'box-header with-border'}).append(
					$('<h3 />' , { 'class' : 'box-title' , 'text' : 'Add Stock Request'}),
					$('<div />' , { 'class' : 'box-tools pull-right'}).append(
						'<button id="btn-new-user" class="btn btn-primary btn-sm" type="button" onClick="setNewEntry();">\
							<i class="fa fa-plus-circle"></i>\
							New\
						</button>\
						<button class="btn btn-box-tool" data-widget="collapse">\
							<i class="fa fa-minus"></i>\
						</button>')),
				$('<div />' , { 'class' : 'box-body' , 'style' : 'min-height:100px'}).append(
					$('<div />' , { 'class' : 'row' }).append(
						$('<div />' , {'class' : 'col-md-12 col-lg-12'}).append(
							$('<h1 />' , { 'class': 'text-center'}).append(
								$('<small />').append(
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add Stock Request'}).append(
										''),
									$('<br />'),
									'Click on the below list for preview'))))),
				$('<div />' , { 'class' : 'box-footer'}));
	}

	
	$(document).on("click",".confirmReceive",function(){
		$this = $(this).data('id');
		promptConfirmation("Are you sure?");
		$("#btnYes").click(function(){
			$('body').append('<div class="modal requestModal" data-keyboard="false" data-backdrop="static">\
					            <div class="modal-dialog">\
					              <div class="modal-content">\
					                <div class="modal-header">\
					                  	<h4 class="modal-title">\
						                    </span>Enter the receipt number\
						                 </h4>\
					                </div>\
					                <div class="modal-body">\
					                  	<div class="form-group">\
										  <label for="request_receipt">Receipt Number:</label>\
										  <input type="text" class="form-control" id="request_receipt" name="request_receipt" value="" placeholder="">\
										  <input type="hidden" class="form-control" id="type" name="type" value="edit">\
										</div>\
										<div class="form-group">\
						                  <label for="d_remarks">Remarks</label>\
						                  <textarea type="text"style="resize: none;" rows="2" class="form-control" id="d_remarks" name="d_remarks" value="" placeholder="Enter remarks"></textarea>\
						                </div>\
						                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Cancel</button>\
										<a href="javascript:void(0)" class="btn btn-default confirmDelivery" data-id="'+$this+'"><i class="fa fa-check"></i>Confirm Delivery</a>\
					                </div>\
					              </div>\
					            </div>\
					          </div>');
			$(".requestModal").modal("show");
		});
	});
	$(document).on("keyup","#request_receipt",function(){
		var receipt = $(this).val();
		$.get('{{URL::Route('checkReceiptDelivery')}}',{ receipt : receipt} , function(response)
		{
			console.log(response);
			if(response.status == "fail"){
				$('#request_receipt').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i> '+response.message+'');
          		$('#request_receipt').closest('.form-group').addClass('has-warning').removeClass('has-success');
			}else if(response.status == "success"){
				$('#request_receipt').closest('.form-group').find('label').html('<i class="fa fa-check"></i> '+response.message+'');
          		$('#request_receipt').closest('.form-group').addClass('has-success').removeClass('has-warning');
			}
			else{
				$('#request_receipt').closest('.form-group').find('label').html(''+response.message+'');
				$('#request_receipt').closest('.form-group').removeClass('has-success').removeClass('has-warning');
			}
		});
	});
	$(document).on("hidden.bs.modal",".requestModal",function(){
	    	$('body').addClass('remove_body_padding');
			$(this).remove();
	});
	$(document).on("click",".confirmDelivery",function(){
		var stock_request = $(this).data('id');
		var checkReceipt = $('#request_receipt').closest('.has-warning').length;
		var receipt = $('#request_receipt').val();
		var remarks = $('#d_remarks').val();
		var _token = "{{ csrf_token() }}";
		$this = $(this);
		if(checkReceipt !=1){
			if(receipt){
				promptConfirmation("Are you sure?");
				$("#btnYes").click(function(){
					$.post('{{URL::Route('requestDelivered')}}',{ _token : _token, stock_request : stock_request,
						receipt : receipt, remarks: remarks} , function(response)
					{
						if(response.status == "success"){
							promptMsg("success","Success.");
							window.location.href = response.link;
						}

					});
				});
			}
			else{
				$('#request_receipt').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i>Delivery Receipt No. - Required');
			    $('#request_receipt').closest('.form-group').addClass('has-warning');
			}
		}
		else{
			$('#request_receipt').focus().blur();
		}

	});
	
</script>
@endsection

