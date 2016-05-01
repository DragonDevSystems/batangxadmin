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
                <tbody id="tbUAList">
                	<tr>
	                  <td>69</td>
	                  <td>Red Horse</td>
	                  <td>75</td>
	                  <td>2</td>
	                  <td>150</td>
	                </tr>
                </tbody>
              </table>
            </div>
          </div>
            <div class="col-xs-6 pull-right">
		      <!--<p class="lead">Amount Due 2/22/2014</p>-->
		      <div class="table-responsive">
		        <table class="table">
		          <tr>
		            <th>No. Items:</th>
		            <td>2</td>
		          </tr>
		          <tr>
		            <th style="width:50%">Subtotal:</th>
		            <td>150</td>
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
								$('<button/>', {'class': 'btn btn-success btn-sm' , 'onClick' : 'getModalClient();', 'type' : 'submit', 'html' : '<i class="fa fa-times-circle"></i>Add Client' }),
								$('<button/>', {'class': 'btn btn-success btn-sm' ,'type' : 'submit', 'html' : '<i class="fa fa-times-circle"></i>Save' }),
								$('<button/>', {'class': 'btn btn-danger btn-sm' ,'type' : 'button','html' : '<i class="fa fa-times-circle"></i>Cancel' }),
								$('<button/>', {'class': 'btn btn-box-tool' ,'type' : 'button', 'data-widget': 'collapse' , 'html' : '<i class="fa fa-minus"></i>' })),
							$('<div />', { 'class' : 'row'}).append(
								$('<div />', {'class' : 'col-md-3 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label' , 'for' : 'customer' , 'text' : 'Customer Name:'}),
											$('<select />' , { 'id':'customer' ,'class':'form-control select2' ,'name':'module'}))))),
							$('<div />', { 'class' : 'row'}).append(
								$('<div />', {'class' : 'col-md-3 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label' , 'for' : 'product' , 'text' : 'Product:'}),
											$('<select />' , { 'id':'product' ,'class':'form-control select2' ,'type':'text','name':'name', 'placeholder':'Enter Name'})))),
								$('<div />', {'class' : 'col-md-2 col-sm-3'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-3'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label', 'text' : 'Remaining Item: 5'}),
											$('<label />' , { 'class' : 'control-label', 'text' : 'Unit Price: P15436.00'})))),
								$('<div />', {'class' : 'col-md-2 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-6 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label', 'for' : 'qty', 'text' : 'Qty.'}),
											$('<input />' , { 'class' : 'control-label', 'type' : 'text', 'name' : 'qty', 'id' : 'qty','disabled' : true})))),
								$('<div />', {'class' : 'col-md-2 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label', 'text' : 'Click or press Enter key to add in the cart'}),
											$('<button />' , { 'class' : 'btn btn-success btn-sm add-cart', 'type' : 'button', 'html' : '<i class="fa fa-shopping-cart"></i> Add to cart'})))))),
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
    	$.get('{{URL::Route('userlist')}}', function(data)
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

     $(document).on("click","#product",function(){
     	alert();
     });
     $(document).on("click",".add-cart",function(){
     	alert();
    	/*var id = $(this).data('id');
    	var amount = $('#input_price').val();
    	var _token = "{{ csrf_token() }}";
    	var checkValue= $("#input_price").val().length;
    	if(checkValue != 0){
	    	$.post('{{URL::Route('addPrice')}}',{ _token: _token ,amount: amount, id : id} , function(response)
			{
				console.log(response);
				if(response.price.length != 0){
					$('#price').empty();
					$('#input_price').val("");
	            	for (var i = 0; i < response.price.length; i++) 
					{
						$('#price').append('<option  value="'+response.price[i].id+'">'+response.price[i].price+'</option>')
					}
					$('#price')
	                  .val(response.current_price) //select option of select2
	                  .trigger("change"); //apply to select2
		          	if(response.status == "success"){
						promptMsg(response.status,response.message);
					}
				}
			});
		}
		else{
			$("#input_price").focus();
		}*/
    });
</script>
@endsection

