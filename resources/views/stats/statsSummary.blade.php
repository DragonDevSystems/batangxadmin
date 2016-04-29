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
        User Access Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> User Access Management</a></li>
        <li class="active">User Access Level</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<!--<div id="div_user-entry" class="box box-success">
		<div class="row">
		<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-aqua">
		<div class="inner">
		<h3 id="nu">0</h3>

		<p>New Users This Month</p>
		</div>
		<div class="icon">
		<i class="ion ion-bag"></i>
		</div>
		<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
		</div>
		</div>

		</div>-->
		<!-- user admin list -->
		<div class="box box-primary">
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
            <div class="overlay tbl-overlay">
	        	<i class="fa fa-spinner fa-spin"></i>
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
		$.get('{{URL::Route('statsSummary',$entry)}}', function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				//$('#tbUAList').empty();
				$('#dtUAList').DataTable().clear().draw();
				for (var i = 0; i < data.length; i++) 
				{
					$('#dtUAList').DataTable().row.add([''+data[i].user_id+'', 
                                                    ''+data[i].un+'', 
                                                    ''+data[i].fname+'', 
                                                    ''+data[i].lname+'', 
                                                    ]).draw();

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
</script>
@endsection

