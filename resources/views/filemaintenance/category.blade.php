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
        File Maintenance
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> File Maintenance</a></li>
        <li class="active">Product Category</li>
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
                  <th>User ID</th>
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
	categoryList();
	function categoryList()
	{
		$.get('{{URL::Route('categoryList')}}', function(data)
		{
			if(data.length != 0)
			{
				for (var i = 0; i < data.length; i++) 
				{
					$('#tbUAList').append('<tr style="cursor:pointer">\
							                  <td>'+data[i].user_id+'</td>\
							                  <td>'+data[i].fname+'</td>\
							                </tr>');
				}
				var table = $("#dtUAList").DataTable();
				$('#dtUAList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        adminInformation(data[0]);
			    } );
			}
			else
			{
				$("#dtUAList").DataTable();
				promptMsg('fail',"No records yet.")
			}

		});
	}

	function adminInformation(uid)
	{
		$('#div_user-entry').append('<div class="overlay">\
	            	<i class="fa fa-spinner fa-spin"></i>\
	            </div>');
		$.get('{{URL::Route('uaal')}}',{ uid: uid}, function(data)
		{
			if(data.length != 0)
			{
				$('#div_user-entry').empty();
				$('#div_user-entry').append(
					$('<div />', {'class': 'box-header with-border' }).append(
						$('<h3 />' , {'class': 'box-title' , 'text': 'View User [Admin]' }),
						$('<div/>', {'class': 'box-tools pull-right' }).append(
							$('<button/>', {'class': 'btn btn-success btn-sm' ,'type' : 'button', 'html' : '<i class="fa fa-times-circle"></i>New' }),
							$('<button/>', {'id' : 'clicker' , 'class': 'btn btn-primary btn-sm' ,'type' : 'button', 'html' : '<i class="fa fa-pencil-square"></i>Edit' }),
							$('<button/>', {'class': 'btn btn-danger btn-sm' ,'type' : 'button', 'html' : '<i class="fa fa-times-circle"></i>Delete' }),
							$('<button/>', {'class': 'btn btn-box-tool' ,'type' : 'button', 'data-widget': 'collapse' , 'html' : '<i class="fa fa-minus"></i>' })),
						$('<div />', { 'class' : 'row'}).append(
							$('<div />', { 'class' : 'col-md-3 col-sm-3'}).append(
								$('<div />' , {'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , {'class' : 'image pull-left'}).append(
										$('<img/>', {'src':'{{env('FILE_MAIN_PATH')}}'+data.uinfo.userDp , 'style':'margin:0px auto;width:40px;height:40px;' , 'class':'img-circle' , 'alt':'User Image'}))),
								$('<div />' , { 'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , { 'class' : 'form-group'}).append(
										$('<label />' , { 'class' : 'control-label' , 'for' : 'username' , 'text' : 'Username'}),
										$('<input />' , { 'id':'username' ,'class':'form-control' ,'type':'text','value' : data.uinfo.un,'name':'username', 'placeholder':'Enter Username' , 'disabled': true}))),
								$('<div />' , { 'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , { 'class' : 'form-group'}).append(
										$('<label />' , { 'class' : 'control-label' , 'for' : 'fname' , 'text' : 'Firstname'}),
										$('<input />' , { 'id':'fname' ,'class':'form-control' ,'type':'text','value' : data.uinfo.fname,'name':'fname', 'placeholder':'Firstname' , 'disabled': true}))),
								$('<div />' , { 'class' : 'col-md-12 col-sm-12'}).append(
									$('<div />' , { 'class' : 'form-group'}).append(
										$('<label />' , { 'class' : 'control-label' , 'for' : 'lname' , 'text' : 'Lastname' }),
										$('<input />' , { 'id':'lname' ,'class':'form-control' ,'type':'text','value' : data.uinfo.lname,'name':'lname', 'placeholder':'Enter Lastname' , 'disabled': true})))),
							$('<div />', {'class' : 'col-md-3 col-sm-3'}).append(
								$('<table />' , {'class' : 'table'}).append(
									$('<thead />').append(
										$('<tr>').append(
											$('<th />' , {'text' : 'Role'}))),
									$('<tbody />' , { 'id' : 'tbody'+data.uinfo.user_id }))))),
				'<div class="box-footer"></div>');

		        $appendItems = $('#tbody'+data.uinfo.user_id);
		        $appendItems.append($('<select />' , { 'id':'module' ,'class':'form-control select2' ,'name':'module', 'disabled': true}).append(
										'<option value="1">Staff</option>',
										'<option value="2">Admin</option>',
										'<option value="3">Super Admin</option>'));
		        $(".select2").select2();
				$('#clicker').click(function() {
			        $('select').each(function() {
			            if ($(this).attr('disabled')) {
			                $(this).removeAttr('disabled');
			            }
			            else {
			                $(this).attr({
			                    'disabled': 'disabled'
			                });
			            }
			        });
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
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<span class="badge bg-purple">0</span><i class="fa fa-plus-circle fa-3x"></i>Add Admin'}).append(
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
		$('#div-entry').empty();
		$('#div-entry').append($('<form />' ,{'id' : 'frmEntry', 'onsubmit' : 'return saveNewEntry()'}).append(
			$('<div />',{ 'class' : 'box-header with-border'}).append(
				$('<h3 />',{'class':'box-title' , 'text' : 'Add New Product Category'}),
				$('<div />', { 'class' : 'box-tools pull-right'}).append(
					$('<button/>', {'class': 'btn btn-success btn-sm' ,'type' : 'submit', 'html' : '<i class="fa fa-times-circle"></i>Save' }),
					$('<button/>', {'class': 'btn btn-danger btn-sm' ,'type' : 'button', 'onClick' : 'defaultDisplay();' , 'html' : '<i class="fa fa-times-circle"></i>Cancel' }),
					$('<button/>', {'class': 'btn btn-box-tool' ,'type' : 'button', 'data-widget': 'collapse' , 'html' : '<i class="fa fa-minus"></i>' })),
				$('<div />', { 'class' : 'row'}).append(
					$('<div />', {'class' : 'col-md-4 col-sm-6'}).append(
						$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
							$('<div />' , {'class' : 'form-group'}).append(
								$('<label />' , { 'class' : 'control-label' , 'for' : 'name' , 'text' : 'Name:'}),
								$('<input />' , { 'id':'name' ,'class':'form-control' ,'type':'text','name':'name', 'placeholder':'Enter Name', 'required' : true})))),
					$('<div />', {'class' : 'col-md-4 col-sm-6'}).append(
						$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
							$('<div />' , {'class' : 'form-group'}).append(
								$('<label />' , { 'class' : 'control-label' , 'for' : 'slug' , 'text' : 'Slug:'}),
								$('<input />' , { 'id':'description' ,'class':'form-control' ,'type':'text','name':'description', 'placeholder':'Enter Description', 'required' : true})))))),
		'<div class="box-footer"></div>'));
    }

    function saveNewEntry()
    {
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
					categoryList();
					defaultDisplay();
				}
				else
				{
					$('.overlay').remove();
				}
				promptMsg(data.status,data.message);
			}
		});
    	return false;
    }
</script>
@endsection

