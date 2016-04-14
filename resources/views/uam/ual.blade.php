@extends('layouts.master')
@section('addHead')
  <title>Dashboard</title>
@endsection

@section('content')
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">FS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>FS</b>Admin</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          @include('includes.userMenu')
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  @include('includes.mainNav')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Access Management
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> User Access Management</a></li>
        <li class="active">User Access Level</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div_user-entry" class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"> Users</h3>
				<div class="box-tools pull-right">
					<button id="btn-new-user" class="btn btn-primary btn-sm" type="button">
						<i class="fa fa-plus-circle"></i>
						New
					</button>
					<button class="btn btn-box-tool" data-widget="collapse">
						<i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="box-body" style="min-height:100px">
				<div class="row">
					<div class="col-md-12 col-lg-12">
						<h1 class="text-center">
							<small>
								<button id="btn-new-user-icn" class="btn btn-app" title="" data-placement="top" data-toggle="tooltip" type="button">
									<span class="badge bg-purple">0</span>
									<i class="fa fa-plus-circle fa-3x"></i>
									Add User
								</button>
								<br>
								Click on the below list for preview
							</small>
						</h1>
					</div>
				</div>
			</div>
			<div class="box-footer">   </div>
		</div>

		<!-- user admin list -->
		<div class="box box-primary">
            <div class="box-header">
            	<h3 class="box-title">Admin Users</h3>
				<div class="box-tools pull-right">
					<h4>
						<a class="label label-primary filter-role" data-role="" href="#">All</a>
						<a class="label label-warning filter-role" data-role="admin" href="#">Admin</a>
						<a class="label label-warning filter-role" data-role="superuser" href="#">Superuser</a>
						<a class="label label-warning filter-role" data-role="user" href="#">User</a>
						<button class="btn btn-box-tool" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</h4>
				</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dtUAList" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>User ID</th>
                  <th>Username</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
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

	
	function adminUserList()
	{
		$.get('{{URL::Route('adminUserList')}}', function(data)
		{
			if(data.length != 0)
			{
				for (var i = 0; i < data.length; i++) 
				{
					$('#tbUAList').append('<tr style="cursor:pointer">\
							                  <td>'+data[i].user_id+'</td>\
							                  <td><img src="{{env('FILE_PATH_CUSTOM')}}'+data[i].userDp+'" style="margin:0px auto;width:30px;height:30px;" class="img-circle" alt="User Image">  '+data[i].un+'</td>\
							                  <td>'+data[i].fname+'</td>\
							                  <td>'+data[i].lname+'</td>\
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
				promptMsg('fail',"No records yet.")
			}

		});
	}
	adminUserList();

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
										'<option value="1">Admin</option>',
										'<option value="0">Staff</option>'));
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
</script>
@endsection

