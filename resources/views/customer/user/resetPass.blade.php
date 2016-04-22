<div class="modal fade" tabindex="-1" role="dialog" id="mdl_reset" style="opacity:1.0 !important;">
	<div class="modal-dialog modal-vertical-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Reset Password</h4>
			</div>
			<div class="modal-body">
				<div class="login-box-body">
					<div class="form-group has-feedback has-error"></div>
					<div class="form-group has-feedback">
						<input type="email" class="form-control" id="resetEmail" name="resetEmail" placeholder="Username or Email">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" onClick="resetPass(); return false;">Submit</button>
						</div>
						<!-- /.col -->
					</div>
				</div>
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>
<label class="control-label"></label>
<script type="text/javascript">
	function resetPass()
    {
        $_token = "{{ csrf_token() }}";
        $email = $("#resetEmail").val();
        $.post('{{URL::Route('resetPass')}}', { _token: $_token, txtUsername: $email}, function(data)
        {
            if(data.status == "success")
            {
            	$('.has-error').empty();
                $('#mdl_reset').modal('hide');
                promptMsg(data.status,data.message);
            }
            else
            {
            	$('.has-error').empty();
            	$('.has-error').append($('<label />' , {'class' :  'control-label' , 'html' : '<i class="fa fa-times-circle-o"></i> '+data.message+''}));
            }
        });
    }
</script>