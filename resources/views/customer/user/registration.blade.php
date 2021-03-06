<div class="modal fade" tabindex="-1" role="dialog" id="mdl_registration" style="opacity:1.0 !important;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registration</h4>
      </div>
      <div class="modal-body">
        <div class="login-box-body">
          <div class="form-group has-feedback has-error errorCheckReg"></div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" id="regusername" name="regusername" placeholder="Username">
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" id="regemail" name="regemail" placeholder="Email">
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="regPassword" id="regPassword">
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Re-type Password" name="re-password" id="re-password">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Firstname" name="fname" id="fname">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Mobile or Phone Number" name="mobile" id="mobile" data-inputmask='"mask": "(99) 9999-99999"' data-mask>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Lastname" name="lname" id="lname">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Date of Birth" name="dob" id="dob" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
              </div>
            </div>
          </div>
         <div class="row">
            <div class="col-md-6">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" id="chk_terms" checked> 
                </label>
                <a href="{{URL::Route('termsandcondition')}}" target="_blank">Terms and condition</a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <a href="javascript:void(0);" class="btn_reset">I forgot my password</a><br>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
              <button type="button" class="btn btn-primary btn-block btn-flat" onClick="regUser(); return false;" id="btnSubmit">Submit</button>
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

<script type="text/javascript">
    $(function () {
      var dt = new Date();
      dt.setFullYear(new Date().getFullYear()-18);
      $('#dob').datepicker(
        {
        viewMode: "years",
        endDate : dt
    });
      $("[data-mask]").inputmask();
      //$(".select2").select2();
    });
    function validateEmail(email)
    { 
      var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
      return re.test(email);
    }

    $(document).keypress(function(e) {
        if(e.which == 13) {
           regUser()
        }
    });
    
    function regUser()
    {
      $remember = $("#chk_terms").is(":checked")
      if($remember)
      {
        $_token = "{{ csrf_token() }}";
        $username = $("#regusername").val();
        $email = $("#regemail").val();
        $password = $("#regPassword").val();
        $repassword = $("#re-password").val();
        $fname = $("#fname").val();
        $lname = $("#lname").val();
        $gender = $("#gender").val();
        $dob = $("#dob").val();
        $mobile = $("#mobile").val();
        $address = $("#address").val();
       
          if($password == $repassword)
          {
            $("#btnSubmit").empty();
            $("#btnSubmit").append('<div class="overlay tbl-overlay"><i class="fa fa-spinner fa-spin"></i></div>');
            $('#btnSubmit').prop('disabled', true);
            $.post('{{URL::Route('postCreate')}}', { _token: $_token, email: $email , username: $username, password: $password, fname: $fname, lname: $lname, gender: $gender, dob: $dob, mobile: $mobile, address: $address}, function(data)
            {
                $("#btnSubmit").empty();
                $("#btnSubmit").append("Submit");
                $('#btnSubmit').prop('disabled', false);
                if(data.status == "success")
                {

                  $('.errorCheckReg').empty();
                  $('#mdl_registration').modal('hide');
                  promptMsg(data.status,data.message)
                }
                else
                {
                  $('.errorCheckReg').empty();
                  $('.errorCheckReg').append($('<label />' , {'class' :  'control-label' , 'html' : '<i class="fa fa-times-circle-o"></i> '+data.message+''}));
                }
                //console.log(data);
            });
          }
          else
          {
            $('.errorCheckReg').empty();
            $('.errorCheckReg').append($('<label />' , {'class' :  'control-label' , 'html' : '<i class="fa fa-times-circle-o"></i>Password does not match.'}));
          }
      }
      else
      {
        $('.errorCheckReg').empty();
        $('.errorCheckReg').append($('<label />' , {'class' :  'control-label' , 'html' : '<i class="fa fa-times-circle-o"></i>To continue, you maust read and accept the terms and condition.'}));
      }

    }
</script>