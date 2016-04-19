<!DOCTYPE html>
<html lang="en">
<head>
	@section('head')
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Tell the browser to be responsive to screen width -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}bootstrap/css/bootstrap.min.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/datepicker/datepicker3.css">
		<!-- jQuery 2.2.0 -->
		<script src="{{env('FILE_PATH_CUSTOM')}}plugins/jQuery/jQuery-2.2.0.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="{{env('FILE_PATH_CUSTOM')}}bootstrap/js/bootstrap.min.js"></script>

		<link href="{{env('FILE_PATH_CUSTOM')}}css/style.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{env('FILE_PATH_CUSTOM')}}css/slider.css" rel="stylesheet" type="text/css" media="all"/>
		<script type="text/javascript" src="{{env('FILE_PATH_CUSTOM')}}js/jquery-1.7.2.min.js"></script> 
		<script type="text/javascript" src="{{env('FILE_PATH_CUSTOM')}}js/move-top.js"></script>
		<script type="text/javascript" src="{{env('FILE_PATH_CUSTOM')}}js/easing.js"></script>
		<script type="text/javascript" src="{{env('FILE_PATH_CUSTOM')}}js/startstop-slider.js"></script>
	@yield('addHead')

	
	<style>
		.modal-vertical-centered {
			transform: translate(0, 50%) !important;
			-ms-transform: translate(0, 50%) !important; /* IE 9 */
			-webkit-transform: translate(0, 50%) !important; /* Safari and Chrome */
		}
	</style>
</head>

<body>
	@yield('content')
</body>
</html>