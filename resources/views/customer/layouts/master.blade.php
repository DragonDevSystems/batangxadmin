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
		<!-- Select2 -->
  		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/select2/select2.min.css">
  		<!-- DataTables -->
  		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/datatables/dataTables.bootstrap.css">
		<!-- Bootstrap 3.3.6 -->
		<script src="{{env('FILE_PATH_CUSTOM')}}bootstrap/js/bootstrap.min.js"></script>

	@yield('addHead')
</head>

<body background="{{env('FILE_PATH_CUSTOM')}}img/diablo3.jpg">
	@yield('content')
</body>
</html>