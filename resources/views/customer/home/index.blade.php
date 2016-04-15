@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<button class="btn btn-success" id="btn_login">Login</button>
<button class="btn btn-success" id="btn_registration">Registration</button>
 	@include('customer.user.login')
 	@include('customer.user.registration')
<script type="text/javascript">
	$('#btn_login').click(function()
		{
			$('#mdl_login').modal('show');
		}
	);
	$('#btn_registration').click(function()
		{
			$('#mdl_registration').modal('show');
		}
	);
	
</script>
@endsection