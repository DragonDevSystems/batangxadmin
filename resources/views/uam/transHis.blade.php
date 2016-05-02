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
  @include('user.walkinRegistration')
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
					userInfoList();
				}
				else
				{
					promptMsg(data.status,data.message);
					$('.overlay').remove();
				}
			}
		});
    }

    function userInfoList()
    {
    	$.get('{{URL::Route('userlist')}}', function(data)
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
					for (var i = 0; i < data.dataInfo.length; i++) 
					{
						$('#dtTHList').DataTable().row.add(['<a href="'+data.dataInfo[i].invoice_link+'" target="_blank">'+data.dataInfo[i].invoice_num+'</a>', 
	                                                    ''+data.dataInfo[i].invoice_date+'', 
	                                                    ''+data.dataInfo[i].cus_name+'', 
	                                                    ''+data.dataInfo[i].status+'', 
	                                                    ]).draw();

					}
					var table = $("#dtTHList").DataTable();
				}
				else
				{
					$('.overlay').remove();
					promptMsg(data.status,data.message)
				}
			}
		});
     });
</script>
@endsection

