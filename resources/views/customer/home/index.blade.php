@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')

	@if(Auth::Check())
		<a href="{{ URL::Route('getLogout') }}" class="btn btn-default btn-flat">Sign out</a>
	@else
		<button class="btn btn-success" id="btn_login">Login</button>
		<button class="btn btn-success" id="btn_registration">Registration</button>
	@endif
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