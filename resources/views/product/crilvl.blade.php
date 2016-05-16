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
        Critical Level Item
      </h1>
    </section>

    <!-- Main content -->
	<section class="content">
		<!-- Invoice list -->
		<div class="box box-danger">
			<!-- /.box-header -->
			<div class="box-body" onload="window.print();">
				<table id="dtUAList" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Product Code</th>
							<th>Name</th>
							<th>Critical Level</th>
							<th>Available Item</th>
						</tr>
					</thead>
					<tbody id="tbUAList"></tbody>
				</table>
				<a href="{{URL::Route('getCritPrint')}}" target="_blank" class="btn btn-default print"><i class="fa fa-print"></i> Print</a>
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
  @include('includes.invoicemodal')
</div>
<script type="text/javascript">
	function inventoryList()
	{
		$.get('{{URL::Route('criLvlList')}}', function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				$('#dtUAList').DataTable().clear().draw();
				for (var i = 0; i < data.length; i++) 
				{
					$('#dtUAList').DataTable().row.add([''+data[i].prod_code+'', 
                                                    ''+data[i].name+'', 
                                                    ''+data[i].cri_lvl+'', 
                                                    ''+data[i].avail_item+'', 
                                                    ]).draw();

				}
				var table = $("#dtUAList").DataTable();
				/*$('#dtUAList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        invoiceModal(data[0]);
			    } );*/
			}
			else
			{
				promptMsg('fail',"No records yet.")
			}

		});
	}
	inventoryList();
</script>
@endsection