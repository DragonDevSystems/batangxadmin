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
        Product List
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="box box-primary">
            <div class="box-header pull-right">
            	<button id="Add" class="btn btn-primary btn-sm Add" type="button">
                  <i class="fa fa-picture-o"></i>
                  Add
                </button>
            </div>
          </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
	$(document).on("hidden.bs.modal",".addProduct",function(){
    	$(this).remove();
	});
	$(document).on("click","#Add",function(){
		$('body').append(
        $('<div />', {'class': 'modal addProduct', 'data-keyboard' : 'false' , 'data-backdrop' : 'static'}).append(
          $('<div />', {'class': 'modal-dialog'}).append(
            $('<div />', {'class': 'modal-content product_wrapper'}).append(
              $('<div />', {'class': 'modal-body'}),
              $('<div />', {'class': 'modal-footer'}).append(
                $('<button />', {'class': 'btn btn-default pull-left' , 'data-dismiss' : 'modal' , 'text' : 'Close'}),
                $('<button />', {'class': 'btn btn-primary saveImage' , 'data-id' : '', 'text' : 'Add Product'})))))
      );
		var template = 
		'<div class="row">'+
	            '<div class="col-md-6">'+
	           		'<div  style="width:100%;padding:10px;padding-bottom:10px;padding-top:10px;border:1px solid #e7e7e7;margin-bottom:10px;">'+
					    '<div class="form-group">'+
					    	'<label>Name of product :</label>'+
					        '<input type="text" class="form-control" id="name" name="name" placeholder="Name of product">'+
					    	'<label>Description</label>'+
					        '<textarea style="resize: none;" class="form-control" rows="2"  placeholder="Enter product description..." name="description"></textarea>'+
					    	'<label>Product Category</label>'+
		                    '<select id="product_cat" class="form-control select2" style="width: 100%;" placeholer="select here..">'+
		                      '<option value="1">test1</option>'+
		                      '<option value="1">test2</option>'+
		                    '</select>'+
		                    '<label>Quantity :</label>'+
					        '<input type="text" class="form-control" id="quantity" name="quantity" placeholder="Quantity">'+
					        '<label>Remarks :</label>'+
					        '<input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">'+
					        '<select id="specs" class="form-control select2" style="width: 100%;">'+
		                      '<option value="1">specs</option>'+
		                      '<option value="1">specs</option>'+
		                    '</select>'+
					    '</div>'+
					'</div>'+
				'</div>'+
				'<div class="col-md-6">'+
			    	'<div  style="width:100%;padding:10px;padding-bottom:10px;padding-top:10px;border:1px solid #e7e7e7;margin-bottom:10px;">'+
			    		'<img src="{{env('FILE_PATH_CUSTOM')}}img/noimage.png" style="width:100%;height:50%;margin-bottom:5px;">'+
			    		'<input type="file"  id="file" name="file" placeholder="Browse">'+
			    	'</div>'+
			    '</div>'+
		'</div>';
	    $(".addProduct").find(".modal-body").append(template);
	$(".addProduct").modal("show");
	$(".select2").select2();
	});
</script>
@endsection

