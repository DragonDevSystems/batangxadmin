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
        Sales Report
      </h1>
    </section>

    <!-- Main content -->
	<section class="content">
		<!-- Invoice list -->

		<div class="box box-primary div-gen">
			<div class="box-header">
            	<div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" data-placeid="" onClick="generateReport();">
                    <i class="fa fa-plus"></i>
                    Generate
                  </button>
                </div>
            </div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					<div class="col-md-4 col-sm-3">
						<div class="form-group">
							<label>Filter</label>
							<select class="form-control select2" id="rType">
								<option value="0">Daily</option>
								<option value="1">Monthly</option>
								<option value="2">Yearly</option>
							</select>
						</div>
					</div>
					<div class="col-md-4 col-sm-3" id="divParam">
						<div class="form-group">
							<label>Date range:</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="reportRange">
							</div>
						</div>
		        	</div>
	            </div>
	        </div>
		</div>

		<div class="row">
		        <div class="col-md-12">
		          <!-- Bar chart -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <i class="fa fa-bar-chart-o"></i>

		              <h3 class="box-title">Sales Chart</h3>
		              <h1 class="box-title h-header"></h1>
		              <div class="box-tools pull-right">
		              	<a href="google.com" class="btn btn-primary btn-sm btn-print" target="_blank" disabled><i class="fa fa-print"></i> Print</a>
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
		              <div id="bar-chart" style="height: 300px;"></div>
		            </div>
		            <!-- /.box-body-->
		            <div class="form-group">
		                <label id="totalSales"></label>
		           	</div>
		          </div>
		      </div>
		  </div>
          <!-- /.box -->
	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">

	$(document).ready(function() {
		$(".select2").select2();
	});

	$(document).on("change", "#rType", function(e){
	    e.preventDefault();
	    $("#divParam").empty();
	    switch($(this).val()) {
		    case "0":
		        $("#divParam").append(
		        	$('<div />', {'class' : 'form-group'}).append(
						'<label>Date range:</label>\
						<div class="input-group">\
							<div class="input-group-addon">\
								<i class="fa fa-calendar"></i>\
							</div>\
							<input type="text" class="form-control pull-right" id="reportRange">\
						</div>'));
		        break;
		    case "1":
		    	 $("#divParam").append(
		        	$('<div />', {'class' : 'form-group'}).append(
						'<label>Select Year:</label>\
						<select class="form-control select2" id="year">\
							<option value="2016">2016</option>\
						</select>'));
		    	 break;
		    case "2":
		        break;
		    default:
		        break;
		}
		return  false;
	});
	$('#reportRange').daterangepicker();
	generateReport();
	function generateReport()
	{
		var daterange = $("#reportRange").val();
		var rtype  = $("#rType").val();
		var year  = $("#year").val();
		
		$(".div-gen").append('<div class="overlay tbl-overlay">\
	        	<i class="fa fa-spinner fa-spin"></i>\
	        </div>');
		$.get('{{URL::Route('generateSalesReport')}}',{date:daterange , rtype : rtype, year : year} , function(data)
        {
        	$(".tbl-overlay").remove();
 			if(data.response.length != 0)
 			{
 				$(".btn-print").removeAttr('disabled');
 				fillChart(data.response);
 			}
 			else
 			{
		 		$(".btn-print").attr({
                    'disabled': 'disabled'
                });
 			}
			$(".h-header").text(data.dateRange);
			$("#totalSales").text(data.allTotal);
			$(".btn-print").attr({
                    'href': data.printTarget
                });
			fillChart(data.response);
        	//var data_val = [["January", 10], ["February", 8], ["March", 100], ["April", 13], ["May", 17], ["June", 9],["January2", 10], ["February3", 8], ["March4", 100], ["April4", 13], ["May4", 17], ["June4", 9]];
        });
	}

    function fillChart(data_val)
    {
		/* END AREA CHART */

		/*
		* BAR CHART
		* ---------
		*/
    	var bar_data = {
	      data: data_val,
	      color: "#3c8dbc",
	    };
	    $.plot("#bar-chart", [bar_data], {
	      grid: {
	      	hoverable: true,
	      	clickable: true,
	        borderWidth: 1,
	        borderColor: "#f3f3f3",
	        tickColor: "#f3f3f3"
	      },
	      series: {
	        bars: {
	          show: true,
	          barWidth: 0.1,
	          align: "center",
	          horizontal: false
	        },
	        series: {
		        lines: { show: true },
		        points: { show: true }
		    }
	      },
	      xaxis: {
	        mode: "categories",
	        tickLength: 0
	      }
	    });
	    /* END BAR CHART */
    }

</script>
@endsection