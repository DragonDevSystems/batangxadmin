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
        Invoices
      </h1>
    </section>

    <!-- Main content -->
	<section class="content">
		<div id="div_user-entry" class="box box-success"></div>
		<!-- user admin list -->
		<div class="box box-primary">
			<!-- /.box-header -->
			<div class="box-body">
				<table id="dtUAList" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Customer Name</th>
							<th>Remarks</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbUAList"></tbody>
				</table>
			</div>
			<div class="overlay tbl-overlay">
	        	<i class="fa fa-spinner fa-spin"></i>
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
	function adminUserList()
	{
		$.get('{{URL::Route('adminUserList')}}', function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				//$('#tbUAList').empty();
				$('#dtUAList').DataTable().clear().draw();
				for (var i = 0; i < data.length; i++) 
				{
					/*$('#tbUAList').append('<tr style="cursor:pointer">\
							                  <td>'+data[i].user_id+'</td>\
							                  <td><img src="{{env('FILE_PATH_CUSTOM')}}'+data[i].userDp+'" style="margin:0px auto;width:30px;height:30px;" class="img-circle" alt="User Image">  '+data[i].un+'</td>\
							                  <td>'+data[i].fname+'</td>\
							                  <td>'+data[i].lname+'</td>\
							                </tr>');*/
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