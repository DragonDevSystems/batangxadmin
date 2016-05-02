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
		<!-- Invoice list -->
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
		$.get('{{URL::Route('invoiceList')}}', function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				$('#dtUAList').DataTable().clear().draw();
				for (var i = 0; i < data.length; i++) 
				{
					$('#dtUAList').DataTable().row.add(['<a href="'+data[i].invoice_link+'" target="_blank">'+data[i].inv_no+'</a>', 
                                                    ''+data[i].customer+'', 
                                                    ''+data[i].remarks+'', 
                                                    ''+data[i].status+'', 
                                                    ]).draw();

				}
				var table = $("#dtUAList").DataTable();
				/*$('#dtUAList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        adminInformation(data[0]);
			    } );*/
			}
			else
			{
				promptMsg('fail',"No records yet.")
			}

		});
	}
	adminUserList();
	/*function adminInformation(data)
	{
		alert(data);
	}*/
</script>
@endsection