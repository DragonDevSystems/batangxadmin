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
							<th>Date</th>
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
  @include('includes.invoicemodal')
</div>
<script type="text/javascript">
	function infoList()
	{
		$.get('{{URL::Route('invoiceList')}}', function(data)
		{
			$('.tbl-overlay').remove();
			if(data.length != 0)
			{
				$('#dtUAList').DataTable().clear().draw();
				for (var i = 0; i < data.length; i++) 
				{
					$('#dtUAList').DataTable().row.add([''+data[i].inv_no+'',
													''+data[i].date+'', 
                                                    ''+data[i].customer+'', 
                                                    ''+data[i].remarks+'', 
                                                    ''+data[i].status+'', 
                                                    ]).draw();

				}
				var table = $("#dtUAList").DataTable();
				$('#dtUAList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        invoiceModal(data[0]);
			    } );
			}
			else
			{
				promptMsg('fail',"No records yet.")
			}

		});
	}
	infoList();
	function invoiceModal(inv_id)
    {
    	$.get('{{URL::Route('invoiceInfoAjax')}}',{ inv_id: inv_id}, function(data)
		{
			if(data.status == "success")
			{
				$('.cust-info').html('To<address><strong>'+data.response.userInfo.fname+' '+data.response.userInfo.lname+'</strong><br>Phone: '+data.response.userInfo.mobile+'<br>Email: '+data.response.userInfo.email+'</address>');
				$('.inv-info').html('<b>Invoice # '+data.response.invoiceNum+'</b><br><b>Status :</b> '+data.response.invoiceStatus+'<br><b>Account No.:</b> '+data.response.accountNum+'');
				for(var i=0; i<data.response.onList[0].productInfo.length ; i++)
				{
					$('#tbodyList').empty();
					$('#tbodyList').append('<tr>\
						                        <td>'+data.response.onList[0].productInfo[i].qty+'</td>\
						                        <td>'+data.response.onList[0].productInfo[i].name+'</td>\
						                        <td>'+data.response.onList[0].productInfo[i].unit_price+'</td>\
						                        <td>'+data.response.onList[0].productInfo[i].price+'</td>\
						                      </tr>');
				}
				$('#tdQty').html(data.response.onList[0].totalQty);
				$('#tdPrice').html(data.response.onList[0].totalPrice);
				$('.action-btn').empty();
				$('.action-btn').append('<a href="'+data.response.invoicelink+'" target="_blank" class="btn btn-sm btn-success">Print</a>');
				if(data.response.invoiceStatus == "Reserved")
				{
					$('.action-btn').append('<button type="button" class="btn btn-sm btn-success" onclick="checkOut('+data.response.userInfo.user_id+','+data.response.inv_id+');">Check-out</button>');
					$('.action-btn').append('<button type="button" class="btn btn-sm btn-danger" onclick="cancelReservation('+data.response.userInfo.user_id+','+data.response.inv_id+');">Cancel Reservation</button>');
				}
				$('#mdl_invoice').modal('show');
			}
			else
			{
				promptMsg(data.status,data.message);
			}
		});
    }
</script>
@endsection