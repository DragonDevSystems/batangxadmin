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
		<div id="div-entry" class="box box-success"></div>

		<!-- user admin list -->
		<div class="box box-primary">
            <div class="box-header">
            	<h3 class="box-title">Product Category</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dtUAList" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>Category ID</th>
                  <th>Category</th>
                </tr>
                </thead>
                <tbody id="tbUAList">
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
	defaultDisplay();
	productList();
	function productList()
	{
		$.get('{{URL::Route('categoryList')}}', function(data)
		{
			$('#tbUAList').empty();
			if(data.length != 0)
			{
				for (var i = 0; i < data.length; i++) 
				{
					$('#tbUAList').append('<tr style="cursor:pointer">\
							                  <td>'+data[i].id+'</td>\
							                  <td>'+data[i].name+'</td>\
							                </tr>');
				}
				var table = $("#dtUAList").DataTable();
				$('#dtUAList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        productInfo(data[0]);
			    } );
			}
			else
			{
				$("#dtUAList").DataTable();
				promptMsg('fail',"No records yet.")
			}

		});
	}

	function productInfo(cid)
	{
		$('#div-entry').append('<div class="overlay">\
	            	<i class="fa fa-spinner fa-spin"></i>\
	            </div>');
		$.get('{{URL::Route('categoryInfo',0)}}',{ cid: cid}, function(data)
		{
			if(data.length != 0)
			{
				$('#div-entry').empty();
				$('#div-entry').append(
					$('<div />', {'class': 'box-header with-border' }).append(
						$('<h3 />' , {'class': 'box-title' , 'text': 'View User [Admin]' }),
						$('<div/>', {'class': 'box-tools pull-right event-btn' }).append(
							'<button class="btn btn-success btn-sm" type="button" onClick="setNewEntry();">\
								<i class="fa fa-plus-circle"></i>\
								New\
							</button>\
							<button id="clicker" class="btn btn-primary btn-sm" type="button">\
								<i class="fa fa-pencil-square"></i>\
								Edit\
							</button>\
							<button class="btn btn-box-tool" data-widget="collapse">\
								<i class="fa fa-minus"></i>\
							</button>'),
						$('<div />', { 'class' : 'row'}).append(
							$('<div />', { 'class' : 'col-md-3 col-sm-3'}).append(
								$('<div />' , { 'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , { 'class' : 'form-group'}).append(
										$('<label />' , { 'class' : 'control-label' , 'for' : 'name' , 'text' : 'Name'}),
										$('<input />' , { 'id':'name' ,'class':'form-control' ,'type':'text','value' : data.name,'name':'name', 'placeholder':'Enter Name' , 'disabled': true}))),
								$('<div />' , { 'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , { 'class' : 'form-group'}).append(
										$('<label />' , { 'class' : 'control-label' , 'for' : 'description' , 'text' : 'Description'}),
										$('<input />' , { 'id':'description' ,'class':'form-control' ,'type':'text','value' : data.description,'name':'description', 'placeholder':'Enter Description' , 'disabled': true})))),
							$('<div />', {'class' : 'col-md-3 col-sm-3'}).append(
								$('<table />' , {'class' : 'table'}).append(
									$('<thead />').append(
										$('<tr>').append(
											$('<th />' , {'text' : 'Status'}))),
									$('<tbody />' , { 'id' : 'tbody'+data.id }))))),
				'<div class="box-footer"></div>');

		        $appendItems = $('#tbody'+data.id);
		        $appendItems.append($('<select />' , { 'id':'drpStatus' ,'class':'form-control select2' ,'name':'module', 'disabled': true}).append(
										'<option value="1">Active</option>',
										'<option value="0">In-Active</option>'));
		        $("#drpStatus").val(data.status);
		        $(".select2").select2();
				$('#clicker').click(function() {
			        $('select,input[type=text]').each(function() {
			            if ($(this).attr('disabled')) {
			                $(this).removeAttr('disabled');
			            }
			            else {
			                $(this).attr({
			                    'disabled': 'disabled'
			                });
			            }
			        });
			        $('.event-btn').find('.btn-sm').each(function() {
			            $(this).remove();
			        });
			        $('.event-btn').prepend(
			            $('<button/>', {'class': 'btn btn-success btn-sm' ,'type' : 'button', 'onClick':'updateRecord('+data.id+');', 'html' : '<i class="fa fa-times-circle"></i>Save' }),
						$('<button/>', {'id' : 'clicker' , 'class': 'btn btn-danger btn-sm', 'onClick':'defaultDisplay();', 'type' : 'button', 'html' : '<i class="fa fa-pencil-sq;are"></i>Cancel' }));
			    });
			}
			else
			{
				promptMsg('fail',"No record found.")
			}
		});
	}

	function defaultDisplay()
	{
	   	$('#div-entry').append('<div class="overlay">\
			        	<i class="fa fa-spinner fa-spin"></i>\
			        </div>');
	   	$('#div-entry').empty();
		$('#div-entry').append(
				$('<div />' , { 'class' : 'box-header with-border'}).append(
					$('<h3 />' , { 'class' : 'box-title' , 'text' : 'Users'}),
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
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add Product'}).append(
										''),
									$('<br />'),
									'Click on the below list for preview'))))),
				$('<div />' , { 'class' : 'box-footer'}));
	}

	function setNewEntry()
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
					$('#div-entry').append($('<form />' ,{'id' : 'frmEntry', 'onsubmit' : 'return saveNewEntry()'}).append(
						$('<div />',{ 'class' : 'box-header with-border'}).append(
							$('<h3 />',{'class':'box-title' , 'text' : 'Add New Product Category'}),
							$('<div />', { 'class' : 'box-tools pull-right'}).append(
								'<button id="btn-new-user" class="btn btn-success btn-sm" type="submit">\
									<i class="fa fa-times-circle"></i>\
									Save\
								</button>\
								<button id="btn-new-user" class="btn btn-danger btn-sm" type="button" onClick="defaultDisplay();">\
									<i class="fa fa-times-circle"></i>\
									Cancel\
								</button>\
								<button class="btn btn-box-tool" data-widget="collapse">\
									<i class="fa fa-minus"></i>\
								</button>')),
							$('<div />', { 'class' : 'row'}).append(
								'<div class="col-md-6 col-sm-6">\
								    <div class="form-group">\
								    	<label for="name">Name of product :</label>\
								        <input type="text" class="form-control" id="name" name="name" placeholder="Name of product">\
								    	<label for="description">Description</label>\
								        <textarea style="resize: none;" class="form-control" rows="2"  placeholder="Enter product description..." name="description" id="description"></textarea>\
								    	<label for="prod_cat">Product Category</label>\
					                    <select id="prod_cat" class="form-control select2" style="width: 100%;" placeholer="select here..">\
					                      <option value="1">test1</option>\
					                      <option value="1">test2</option>\
					                    </select>\
					                    <label for="specs">Specification :</label>\
								        <select id="specs" class="form-control select2" style="width: 100%;">\
					                      <option value="1">specs</option>\
					                      <option value="1">specs</option>\
					                    </select>\
								    </div>\
							</div>\
							<div class="col-md-6 col-sm-6">\
						    	<div  style="width:100%;padding:10px;padding-bottom:10px;padding-top:10px;border:1px solid #e7e7e7;margin-bottom:10px;">\
						    		<img src="{{env('FILE_PATH_CUSTOM')}}img/noimage.png" style="width:100%;height:50%;margin-bottom:5px;">\
						    		<input type="file"  id="file" name="file" placeholder="Browse">\
						    	</div>\
						    </div>')),
					'<div class="box-footer"></div>');
				$(".select2").select2();
				}
				else
				{
					promptMsg(data.status,data.message);
					$('.overlay').remove();
				}
			}
		});
    }

    function saveNewEntry()
    {
    	promptConfirmation("Are you sure you want to add this new category?");
    	/*$('#btnYes').click(function() {
	    	$_token = "{{ csrf_token() }}";
	    	$name = $('#name').val();
	    	$description = $('#description').val();
	    	$('#div-entry').append('<div class="overlay">\
				        	<i class="fa fa-spinner fa-spin"></i>\
				        </div>');
	    	$.post('{{URL::Route('addCategory')}}',{ _token: $_token, name: $name, description: $description}, function(data)
			{
				if(data.length != 0)
				{
					if(data.status == "success")
					{
						productList();
						defaultDisplay();
					}
					else
					{
						$('.overlay').remove();
					}
					promptMsg(data.status,data.message);
				}
			});
    	});*/
    	return false;
    }
</script>
@endsection

