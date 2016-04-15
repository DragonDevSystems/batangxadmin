<div class="modal fade" tabindex="-1" role="dialog" id="mdl_registration" style="opacity:0.6 !important;">
  <div class="modal-dialog">
    <div class="modal-content modal-vertical-centered">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registration</h4>
      </div>
      <div class="modal-body">
                <div class="login-box-body">
                  <div class="form-group has-feedback has-error"></div>
                  <div class="form-group has-feedback">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Username or Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Re-type Password" name="password" id="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="row">
                  <div class="form-group col-xs-12">
                    <div class="col-md-6 col-sm-5 ">
                    <input type="text" class="form-control" placeholder="Firstname" name="password" id="password">
                    </div>
                    <div class="col-md-6 col-sm-5">
                    <input type="text" class="form-control" placeholder="Lastname" name="password" id="password">
                    </div>
                  </div>
                  <div class="form-group col-xs-12">
                    <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control" placeholder="Gender" name="password" id="password">
                    </div>
                    <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control" placeholder="Date of Birth" name="password" id="password">
                    </div>
                  </div>
                  <div class="form-group col-xs-12">
                    <input type="text" class="form-control" placeholder="Mobile or Phone Number" name="password" id="password">
                  </div>
                  <div class="form-group col-xs-12">
                      <textarea style="resize: none;" class="form-control" rows="5"  placeholder="Enter Address..."></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-8">
                      <a href="#">I forgot my password</a><br>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                      <button type="submit" class="btn btn-primary btn-block btn-flat" onClick="validateCreds(); return false;">Log In</button>
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