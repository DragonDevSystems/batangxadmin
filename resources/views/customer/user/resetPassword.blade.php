@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
		@include('customer.includes.mainMenu')
	</div>
	<div class="main">
		<div class="content">
			<div class="section group">
				<div class="col span_2_of_3">
					<div class="contact-form">
						<h2>Hi {{$userInfo['first_name']}}!</h2>
						<h1>Welcome back! You may now reset your password.</h1>
						<div class="form_field">
							<span><label>Password</label></span>
							<span><input id="pass1" name="pass1" type="password" class="textbox"></span>
						</div>
						<div class="form_field">
							<span><label>Re-type Password</label></span>
							<span><input id="pass2" name="pass2" type="password" class="textbox"></span>
						</div>
						<div class="form_field">
							<input type="button" class="btn btn-success btn-sm" value="Reset Now" onClick="validateRes();"></input>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	@include('customer.includes.footer')

<script type="text/javascript">
	
	function validateRes()
	{
		$pass1 = $("#pass1").val();
		$pass2 = $("#pass2").val();
		if(($pass1 != null && $pass1 != "") && ($pass2 != null && $pass2 != ""))
		{
			if(passMatchCheck())
			{
				processResetPass();
			}
		}
		else
		{
			promptMsg("fail","Please fill out all fields.")
		}
	}

	function passMatchCheck()
	{
		$pass1 = $("#pass1").val();
		$pass2 = $("#pass2").val();
		if($pass1 != $pass2)
		{
			$("#pass1").val("");
			$("#pass2").val("");
			promptMsg("fail","Password thus not match. Please Try again.")
			return false;
		}
		else
		{
			return true;
		}
	}

	function processResetPass()
		{
			$pass1 = $("#pass1").val();
			$vcode = "{{$code}}";
			$userid = "{{$userInfo['user_id']}}";
			$_token = "{{ csrf_token() }}";
			$.post('{{URL::Route('processResetPass')}}', { _token: $_token , pass: $pass1 , vcode: $vcode , userid: $userid}, function(response)
			{
				if(response.status == "success")
				{
					promptMsg(response.status,response.message);
				}
				else
				{
					promptMsg(response.status,response.message);
				}
				window.location.replace('{{URL::Route('cusIndex')}}');	
			});
		}
</script>

@endsection