<div class="modal fade" tabindex="-1" role="dialog" id="mdl_login" style="opacity:0.5 !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Login</h4>
			</div>
			<div class="modal-body">
				<div class="login-box-body">
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

					<a href="#">I forgot my password</a><br>
					<!--<a href="register.html" class="text-center">Register a new membership</a>-->
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
				<button class="btn btn-outline" type="button" data-dismiss="modal" id="btnYes">Register Now</button>
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>