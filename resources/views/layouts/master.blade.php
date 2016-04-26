<!DOCTYPE html>
<html lang="en">
<head>
	@section('head')
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/iCheck/flat/blue.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/jvectormap/jquery-jvectormap-1.2.2.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/datepicker/datepicker3.css">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/daterangepicker/daterangepicker-bs3.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
		<!-- jQuery 2.2.0 -->
		<script src="{{env('FILE_PATH_CUSTOM')}}plugins/jQuery/jQuery-2.2.0.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<!-- Go Map -->
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXLVRaZrYJiRQLqJAUTmqKkU0qmTJnxeo&signed_in=false&libraries=places"></script>
		<script src="{{env('FILE_PATH_CUSTOM')}}js/jquery.gomap-1.3.3.min.js"></script>
		<!-- Select2 -->
  		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/select2/select2.min.css">
  		<!-- DataTables -->
  		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/datatables/dataTables.bootstrap.css">
  		<!-- Theme style -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}dist/css/skins/_all-skins.min.css">
		<!-- Bootstrap 3.3.6 -->
		<script src="{{env('FILE_PATH_CUSTOM')}}bootstrap/js/bootstrap.min.js"></script>
		<!-- bootstrap wysihtml5 - text editor -->
  		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

		<!-- FLOT CHARTS -->
		<!--<script src="{{env('FILE_PATH_CUSTOM')}}plugins/flot/jquery.flot.min.js"></script>-->
		<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
		<!--<script src="{{env('FILE_PATH_CUSTOM')}}plugins/flot/jquery.flot.resize.min.js"></script>-->
		<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
		<!--<script src="{{env('FILE_PATH_CUSTOM')}}plugins/flot/jquery.flot.pie.min.js"></script>-->
		<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
		<!--<script src="{{env('FILE_PATH_CUSTOM')}}plugins/flot/jquery.flot.categories.min.js"></script>-->
		<!-- Page script --> 
		<style>
			.modal {
			  text-align: center;
			  padding: 0!important;
			}

			.modal:before {
			  content: '';
			  display: inline-block;
			  height: 100%;
			  vertical-align: middle;
			  margin-right: -4px;
			}

			.modal-dialog {
			  display: inline-block;
			  text-align: left;
			  vertical-align: middle;
			}
		</style>
	@yield('addHead')
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<script type="text/javascript">
		function promptMsg($result,$message)
		{
			$("#mdl_msg").text($message);
			$("#prompt_modal").removeClass("modal-danger");
			$("#prompt_modal").removeClass("modal-success");
			if($result == "success"){
				$("#prompt_modal").addClass("modal-success");
			}
			else
			{
				$("#prompt_modal").addClass("modal-danger");
			}

			$("#prompt_modal").modal("show");
		}
		function promptConfirmation($message)
		{
			//$("#mdl_confirmation_msg").text($message);
			$('body').append('<div class="modal fade modal-info" tabindex="-1" role="dialog" id="prompt_confirmation">\
								  <div class="modal-dialog">\
								    <div class="modal-content">\
								      <div class="modal-header">\
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
								          <span aria-hidden="true">&times;</span></button>\
								        <h4 class="modal-title">Comfirmation Message...</h4>\
								      </div>\
								      <div class="modal-body">\
								        <p id="mdl_confirmation_msg">'+$message+'</p>\
								      </div>\
								      <div class="modal-footer">\
								        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>\
								        <button class="btn btn-outline" type="button" data-dismiss="modal" id="btnYes">Save changes</button>\
								      </div>\
								    </div>\
								  </div>\
								</div>');
			$("#prompt_confirmation").modal("show");
		}
	    $(document).on("hidden.bs.modal","#prompt_confirmation",function(){
			$(this).remove();
		});
		/*
		function promptConfirmation($message)
		{
			$("#mdl_confirmation_msg").text($message);
			$("#prompt_confirmation").modal("show");
		}*/

	</script>
	@if(Session::has('success'))
		<script type="text/javascript">
			promptMsg('success',"{{Session::get('success')}}");
		</script>
	@elseif (Session::has('fail'))
		<script type="text/javascript">
			promptMsg('fail',"{{Session::get('fail')}}");
		</script>
	@endif
	@yield('content')
	<div class="modal fade" tabindex="-1" role="dialog" id="prompt_modal">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">System Message...</h4>
	      </div>
	      <div class="modal-body">
	        <p id="mdl_msg"></p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	    <!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>

	<!--
	<div class="modal fade modal-info" tabindex="-1" role="dialog" id="prompt_confirmation">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Confirmation Message...</h4>
	      </div>
	      <div class="modal-body">
	        <p id="mdl_confirmation_msg"></p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
	        <button class="btn btn-outline" type="button" data-dismiss="modal" id="btnYes">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>-->
	
	<!-- ./wrapper -->
	<!-- Sparkline -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/sparkline/jquery.sparkline.min.js"></script>
	<!-- jvectormap -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/knob/jquery.knob.js"></script>
	<!-- daterangepicker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/daterangepicker/daterangepicker.js"></script>
	<!-- datepicker -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<!-- Slimscroll -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/fastclick/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="{{env('FILE_PATH_CUSTOM')}}dist/js/app.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="{{env('FILE_PATH_CUSTOM')}}dist/js/demo.js"></script>
	<!-- Select2 -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/select2/select2.full.min.js"></script>
	<!-- DataTables -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="{{env('FILE_PATH_CUSTOM')}}dist/js/pages/dashboard.js"></script>
	<!-- iCheck -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/iCheck/icheck.min.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script src="{{env('FILE_PATH_CUSTOM')}}plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
</body>
</html>