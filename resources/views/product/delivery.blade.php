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
        <small>Control panel</small>
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
                  <button id="editProduct" class="btn btn-info btn-sm " type="button" disabled>
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="product_list" class="table table-bordered table-hover">
                <thead>
                <?php 
                    $headers = Schema::getColumnListing('prod_delivery');
                    $include = ["id","receipt_num","prod_id","qty"]; 
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
	});
	function setNewEntry(id)
	{
		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',0)}}',{ event: "add"}, function(data)
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
																<div class="col-md-4">\
																	<form id="formAddProduct" method="post" role="form">\
																		<div class="form-group">\
																		  <label for="receipt_num">Delivery Receipt No.</label>\
																		  <input type="text" class="form-control" id="receipt_num" name="receipt_num" value="" placeholder="Enter receipt number." required>\
																		</div>\
																		<div class="row">\
																			<div class="col-md-8">\
																				<div class="form-group">\
																	                <label for="product">Choose Product</label>\
																	                <select id="product" name="product" class="form-control select2" style="width: 100%;" required>\
																	                </select>\
																	            </div>\
																	        </div>\
																	        <div class="col-md-4">\
																				<div class="form-group">\
																	                <label for="qty">Quantity</label>\
																		  			<input type="text" class="form-control" id="qty" name="qty" value="" placeholder="Enter amount here." required>\
																	            </div>\
																	        </div>\
															            </div>\
																		<div class="box-tools pull-right">\
															            <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-undo" aria-hidden="true"></i>Add product.</button>\
															          </div>\
																	</form>\
																	<form id="uploadProductImage" role="form" enctype ="multipart/form-data" method="post">\
																		<input type="file" id="file" name="file[]" style="display:none" multiple>\
																		<input type="hidden" id="processType" value="old" name="processType">\
																		<input type="hidden" value="{{ csrf_token() }}" name="_token">\
																		<button type="submit" class="btn btn-primary submitImage" style="display:none"></button>\
																	</form>\
																</div>\
																<div class="col-md-8 browse">\
																</div>\
															</div>\
															<div class="box-footer">\
																<button  type="button" class="btn btn-primary addProduct">as</button>\
															</div>\
								           				</div>\
													');
				}
			}
		});
	}
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
</script>
@endsection

