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
        Walk-in User
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div_user-entry" class="box box-success"></div>
		<!-- user admin list -->
		<div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dtUAList" class="table table-striped">
                <thead>
                <tr>
                  <th>Product Id</th>
                  <th>Product Name</th>
                  <th>Unit Price</th>
                  <th>Qty</th>
                  <th>Sub-Total</th>
                </tr>
                </thead>
                <tbody id="tbUAList"></tbody>
              </table>
            </div>
          </div>
            <div class="col-xs-6 pull-right">
		      <!--<p class="lead">Amount Due 2/22/2014</p>-->
		      <div class="table-responsive">
		        <table class="table">
		          <tr>
		            <th>No. Items:</th>
		            <td id="tdQty">0</td>
		          </tr>
		          <tr>
		            <th style="width:50%">Subtotal:</th>
		            <td id="tdTotal">0.00</td>
		          </tr>
		          <tr>
		          </tr>
		        </table>
		      </div>
		    </div>
		   </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
  @include('user.walkinRegistration')
</div>
<script type="text/javascript">
	function getModalClient()
	{
		$('#mdl_registration').modal('show');
	}

	setNewEntry();
	function setNewEntry()
	{
		$('#div_user-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',["add","uam"])}}', function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$('#div_user-entry').empty();
					$('#div_user-entry').append(
						$('<div />',{ 'class' : 'box-header with-border'}).append(
							$('<h3 />',{'class':'box-title' , 'text' : 'Process Walk-in Client'}),
							$('<div />', { 'class' : 'box-tools pull-right'}).append(
								$('<button/>', {'class': 'btn btn-success btn-sm' , 'onClick' : 'getModalClient();', 'type' : 'submit', 'html' : '<i class="fa fa-times-circle"></i>Add Client' })),
							$('<div />', { 'class' : 'row'}).append(
								$('<div />', {'class' : 'col-md-3 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label' , 'for' : 'customer' , 'text' : 'Customer Name:'}),
											$('<select />' , { 'id':'customer' ,'class':'form-control select2' ,'name':'customer'}))))),
							$('<div />', { 'class' : 'row'}).append(
								$('<div />', {'class' : 'col-md-3 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label' , 'for' : 'product' , 'text' : 'Product:'}),
											$('<select />' , { 'id':'product' ,'class':'form-control select2' ,'type':'text','name':'product'})))),
								$('<div />', {'class' : 'col-md-2 col-sm-3'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-3'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label item-remain', 'text' : 'Remaining Item: 0'}),
											$('<label />' , { 'class' : 'control-label unit-price', 'text' : 'Unit Price: 0'})))),
								$('<div />', {'class' : 'col-md-2 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-6 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label', 'for' : 'qty', 'text' : 'Qty.'}),
											$('<input />' , { 'class' : 'control-label', 'type' : 'text', 'name' : 'qty', 'id' : 'qty','disabled' : true})))),
								$('<div />', {'class' : 'col-md-2 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label', 'text' : 'Click or press Enter key to add in the cart'}),
											$('<button />' , { 'class' : 'btn btn-success btn-sm add-cart', 'id' : 'addCart', 'type' : 'button', 'html' : '<i class="fa fa-shopping-cart"></i> Add to cart','disabled' : true})))))),
					'<div class="box-footer"></div>');
					$(".select2").select2();
					userInfoList();
					allProduct();
				}
				else
				{
					promptMsg(data.status,data.message);
					$('.overlay').remove();
				}
			}
		});
    }

    function userInfoList()
    {
    	$.get('{{URL::Route('cuslist')}}', function(data)
		{
			$('#customer').empty();
			if(data.length != 0)
			{
				for(var i=0 ; i < data.userInfoList.length ; i++)
				{
					$('#customer').append('<option value="'+data.userInfoList[i].user_id+'">'+data.userInfoList[i].fname+' '+data.userInfoList[i].lname+'</option>');
				}
			}
		});
    }

    function allProduct()
    {
    	$.get('{{URL::Route('allProduct')}}', function(data)
		{
			$('#product').empty();
			if(data.length != 0)
			{
				for(var i=0 ; i < data.length ; i++)
				{
					$('#product').append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
				}
			}
		});
    }

     $(document).on("change","#product",function(){
     	var prod_id = $(this).val();
     	$('#div_user-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
     	$.get('{{URL::Route('getProductInfo')}}',{ product: prod_id}, function(data)
		{
			if(data.length != 0)
			{
				$('.overlay').remove();
				$('.item-remain').text("Remaining Item: "+data.remaining_inv);
				$('.unit-price').html("Unit Price: "+data.current_price_value);
				$('#qty').val("");
				if(data.remaining_inv != 0 && data.current_price_value != "No Price Available")
				{
					$('button[id=addCart],input[type=text]').removeAttr('disabled');
				}
				else
				{
					$('button[id=addCart],input[type=text]').attr({'disabled': 'disabled'});
				}
			}
		});
     });

     $(document).on("change","#customer",function(){
      	onCartList();
     });
     $(document).on("click",".add-cart",function(){

		var _token = "{{ csrf_token() }}";
		var cus_id =  $('#customer').val();
		var pid = $('#product').val();
		var qty = $('#qty').val();
		$.post('{{URL::Route('addToCart',0)}}',{ _token: _token ,  prod_id: pid ,  qty: qty , cus_id: cus_id, type: 2},function(response)
		 	{
		 		if(response.length != 0)
				{
					promptMsg(response.status,response.message)
					onCartList();
				}
		 	});
    });
    $(document).on("click",".btn-checkout",function(){
    	promptConfirmation("Are you sure you want to checkout this transaction?");
    	$('#btnYes').click(function() {
    		var _token = "{{ csrf_token() }}";
			var cus_id =  $('#customer').val();
    		$.post('{{URL::Route('walkinCheckOut')}}',{ _token: _token , cus_id: cus_id, type: 2},function(response)
		 	{
		 		if(response.length != 0)
				{
					promptMsg(response.status,response.message)
					onCartList();
					window.open(response.invoicelink, '_blank');
				}
		 	});
    	});
    	return false;
	});
    function onCartList()
	{
		var cus_id = $('#customer').val();
		$.get('{{URL::Route('onCartList',[0,2])}}',{cus_id : cus_id}, function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				$('#dtUAList').DataTable().clear().draw();
				if(data[0].productInfo.length > 0)
				{
					$('.box-tools').append(
								$('<button/>', {'class': 'btn btn-success btn-sm btn-checkout' ,'type' : 'submit', 'html' : '<i class="fa fa-times-circle"></i>Checkout' }),
								$('<button/>', {'class': 'btn btn-danger btn-sm btn-cancel' ,'type' : 'button','html' : '<i class="fa fa-times-circle"></i>Cancel' }));
				}
				else
				{
					$('.btn-cancel').remove();
					$('.btn-checkout').remove();
				}
				for($x=0 ; $x < data[0].productInfo.length ;$x++)
		   		{
					$('#dtUAList').DataTable().row.add([''+data[0].productInfo[$x].prod_id+'', 
                                                    ''+data[0].productInfo[$x].name+'', 
                                                    ''+data[0].productInfo[$x].unit_price+'', 
                                                    ''+data[0].productInfo[$x].qty+'', 
                                                    ''+data[0].productInfo[$x].price+'', 
                                                    ]).draw();

				}
				$("#tdQty").text(data[0].totalQty);
				$("#tdTotal").html(data[0].totalPrice);
				var table = $("#dtUAList").DataTable();
			}
			else
			{
				promptMsg('fail',"No records yet.")
			}

		});
	}
</script>
@endsection

