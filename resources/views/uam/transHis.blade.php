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
        Transaction History
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div_entry" class="box box-success"></div>
		<!-- user admin list -->
		<div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dtTHList" class="table table-striped">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>Customer Name</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody id="tbUAList"></tbody>
              </table>
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
	function getModalClient()
	{
		$('#mdl_registration').modal('show');
	}

	setNewEntry();
	function setNewEntry()
	{
		$('#div_entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',["view","uam"])}}', function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$('#div_entry').empty();
					$('#div_entry').append(
						$('<div />',{ 'class' : 'box-header with-border'}).append(
							$('<div />', { 'class' : 'row'}).append(
								$('<div />', {'class' : 'col-md-3 col-sm-6'}).append(
									$('<div />', {'class' : 'col-md-12 col-sm-12'}).append(
										$('<div />' , {'class' : 'form-group'}).append(
											$('<label />' , { 'class' : 'control-label' , 'for' : 'customer' , 'text' : 'Customer Name:'}),
											$('<select />' , { 'id':'customer' ,'class':'form-control select2' ,'name':'customer'})))))),
					'<div class="box-footer"></div>');
					$(".select2").select2();
					$("#dtTHList").DataTable();
					infoList();
				}
				else
				{
					promptMsg(data.status,data.message);
					$('.overlay').remove();
				}
			}
		});
    }

    function infoList()
    {
    	$.get('{{URL::Route('cuslist')}}', function(data)
		{
			$('#customer').empty();
			if(data.length != 0)
			{
				for(var i=0 ; i < data.userInfoList.length ; i++)
				{
					$('#customer').append('<option value="'+data.userInfoList[i].user_id+'">'+data.userInfoList[i].fname+' '+data.userInfoList[i].lname+'</option>');
				}
			}
		});
    }

    $(document).on("change","#customer",function(){
     	var cus_id = $(this).val();
     	$('#div_entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
     	$.get('{{URL::Route('getInvoiceList',0)}}',{ cus_id: cus_id}, function(data)
		{
			if(data.length != 0)
			{
				if(data.status = "success")
				{
					$('.overlay').remove();
					$('#dtTHList').DataTable().clear();
					for (var i = 0; i < data.dataInfo.length; i++) 
					{
						$('#dtTHList').DataTable().row.add([''+data.dataInfo[i].invoice_num+'', 
	                                                    ''+data.dataInfo[i].invoice_date+'', 
	                                                    ''+data.dataInfo[i].cus_name+'', 
	                                                    ''+data.dataInfo[i].status+'', 
	                                                    ]).draw();

					}
					var table = $("#dtTHList").DataTable();
					$('#dtTHList tbody').on('click', 'tr', function () {
			        var data = table.row( this ).data();
			        invoiceModal(data[0]);
			    } );
				}
				else
				{
					$('.overlay').remove();
					promptMsg(data.status,data.message)
				}
			}
		});
     });

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

