@extends('layouts.master')
@section('addHead')
  <title>Dashboard</title>
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
        Delivery List
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Delivery</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div-entry" class="box box-success">
		</div>

		<!-- product list -->
		<div class="box box-primary">
            <div class="box-header">
            	<h3 class="box-title">Delivery List</h3>
            	<div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" data-placeid="" onClick="setNewEntry();">
                    <i class="fa fa-plus"></i>
                    Add Delivery
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
                  @foreach($headers as $header)
                    @if(in_array($header, $include))
                       <th>{{$header}}</th>
                    @endif
                  @endforeach
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
		var table = $('#delivery_list').DataTable();
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
		$.get('{{URL::Route('getDeliveryProduct')}}',{id : id}, function(data)
		{
			console.log(data)
	      		$('#div-entry').empty();
				$('#div-entry').append('<div class="box-header with-border">\
			          <h3 class="box-title">Receipt Information</h3>\
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
																  <label for="receipt_num">Delivery Receipt No.</label>\
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
							           				</div>\
												');
				var table2 = $('#qty_list').DataTable();
		        $('#qty_list').DataTable().clear().draw();
		        for (var i = 0; i < data.length; i++) 
		        {
		        	if(i == 0){
		        		$('#receipt_num').val(data[i].receipt);
		        	}
		        	if(data.length > 1){
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

							$.post('{{URL::Route('deleteDeliveryProduct')}}',{ _token: _token ,product: product, name : name , qty : qty ,
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
					$.get('{{URL::Route('getReceiptList',0)}}',{ event: "add"}, function(data)
					{
						if(data.length != 0)
				      	{
					        $('#delivery_list').DataTable().clear().draw();
					        for (var i = 0; i < data.length; i++) 
					        {
					        	$('#delivery_list').DataTable().row.add([''+data[i].id+'', 
                                                    ''+data[i].receipt_num+'', 
                                                    ''+data[i].created_at+'', 
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
						          <h3 class="box-title">Add Delivery</h3>\
						          <div class="box-tools pull-right">\
						            <button type="button" class="btn btn-danger btn-sm"  onClick="defaultDisplay()"><i class="fa fa-undo" aria-hidden="true"></i></button>\
						          </div>\
						        </div>');

							$('#div-entry').append('<div class="box-body formAddProduct">\
															<div class="row">\
																<div class="col-md-5">\
																	<form id="formAddProduct" method="post" role="form">\
																		<div class="form-group">\
																		  <label for="receipt_num">Delivery Receipt No.</label>\
																		  <input type="text" class="form-control" id="receipt_num" name="receipt_num" value="" placeholder="Enter receipt number." >\
																		</div>\
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
																<button  type="button" class="btn btn-primary add_delivery">Add Delivery</button>\
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
				var _token = "{{ csrf_token() }}";
				var check = $("#qty_list").dataTable().fnSettings().aoData.length;
				$this = $(this);
				if(checkReceipt !=1){
					if(receipt && check != 0){
						promptConfirmation("Are ou sure you want to add this receipt ?");
			     		$("#btnYes").click(function(){
			     			$.post('{{URL::Route('addReceipt')}}',{ _token: _token ,receipt: receipt} , function(response)
							{
								if(response.status == "success"){
									defaultDisplay();
									getReceiptList();
								}
							});
			     		});
					}
					else if(!receipt){
						$('#receipt_num').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i>Delivery Receipt No. - Required');
			          	$('#receipt_num').closest('.form-group').addClass('has-warning');
					}
					else{
						promptMsg("fail","Please add product.");
					}
				}
				else{
					$('#receipt_num').focus().blur();
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

						$.post('{{URL::Route('deleteDeliveryProduct')}}',{ _token: _token ,product: product, name : name , qty : qty } , function(response)
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
		$.post('{{URL::Route('addDelivery')}}',{ _token: _token ,product: product, qty : qty, check : check ,
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
					$('<h3 />' , { 'class' : 'box-title' , 'text' : 'Add Product'}),
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
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add Delivery'}).append(
										''),
									$('<br />'),
									'Click on the below list for preview'))))),
				$('<div />' , { 'class' : 'box-footer'}));
	}

	$(document).on("keyup","#receipt_num",function(){
		var receipt = $(this).val();
		$.get('{{URL::Route('checkReceiptDelivery')}}',{ receipt : receipt} , function(response)
		{
			console.log(response);
			if(response.status == "fail"){
				$('#receipt_num').closest('.form-group').find('label').html('<i class="fa fa-exclamation-triangle"></i> '+response.message+'');
          		$('#receipt_num').closest('.form-group').addClass('has-warning').removeClass('has-success');
			}else if(response.status == "success"){
				$('#receipt_num').closest('.form-group').find('label').html('<i class="fa fa-check"></i> '+response.message+'');
          		$('#receipt_num').closest('.form-group').addClass('has-success').removeClass('has-warning');
			}
			else{
				$('#receipt_num').closest('.form-group').find('label').html(''+response.message+'');
				$('#receipt_num').closest('.form-group').removeClass('has-success').removeClass('has-warning');
			}
		});
	});
</script>
@endsection

