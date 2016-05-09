<div class="modal fade" tabindex="-1" role="dialog" id="mdl_login" style="opacity:1.0 !important;">
	<div class="modal-dialog modal-vertical-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Login</h4>
			</div>
			<div class="modal-body">
				<div class="login-box-body">
					<div class="form-group has-feedback has-error errorCheckLogin "></div>
					<div class="form-group has-feedback">
						<input type="email" class="form-control" id="email" name="email" placeholder="Username or Email">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" placeholder="Password" name="password" id="password">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<div class="checkbox icheck">
								<label>
									<input type="checkbox" id="chk_remember"> Remember Me
								</label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" onClick="validateCreds(); return false;">Log In</button>
						</div>
						<!-- /.col -->
					</div>

					<a href="javascript:void(0);" class="btn_reset">I forgot my password</a><br>
					<!--<a href="register.html" class="text-center">Register a new membership</a>-->
				</div>
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>
<label class="control-label"></label>
<script type="text/javascript">
	$(document).keypress(function(e) {
	    if(e.which == 13) {
	       validateCreds()
	    }
	});
	
	function validateCreds()
    {
        $_token = "{{ csrf_token() }}";
        $email = $("#email").val();
        $pass = $("#password").val();
        $source = "client";
        $remember = $("#chk_remember").is(":checked");
        $.post('{{URL::Route('postLogin')}}', { _token: $_token, txtUsername: $email , txtPassword: $pass ,remember: $remember,source: $source}, function(data)
        {
            if(data == 1)
            {
                window.location.replace('{{URL::Route('cusIndex')}}');
            }
            else
            {
            	$('.errorCheckLogin').empty();
            	$('.errorCheckLogin').append($('<label />' , {'class' :  'control-label' , 'html' : '<i class="fa fa-times-circle-o"></i> '+data.message+''}));
            }
            //console.log(data);
        });
    }
</script>