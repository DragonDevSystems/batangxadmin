<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GameXtreme | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <!-- Bootstrap 3.3.6 -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{env('FILE_PATH_CUSTOM')}}plugins/iCheck/square/blue.css">

  <!-- jQuery 2.2.0 -->
  <script src="{{env('FILE_PATH_CUSTOM')}}plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="{{env('FILE_PATH_CUSTOM')}}bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="{{env('FILE_PATH_CUSTOM')}}plugins/iCheck/icheck.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{URL::Route('cusIndex')}}"><b><img width="226" height="77" src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Log in to start your session</p>

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
    <!--<a href="register.html" class="text-center">Register a new membership</a>-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<div class="modal modal-danger fade" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Failed to Log in!</h4>
      </div>
      <div class="modal-body">
        <p id="mdl_msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>
    $(document).keypress(function(e) {
        if(e.which == 13) {
           validateCreds()
        }
    });
    
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    function validateCreds()
    {
        $_token = "{{ csrf_token() }}";
        $email = $("#email").val();
        $pass = $("#password").val();
        $source = "admin";
        $remember = $("#chk_remember").is(":checked");
        $.post('{{URL::Route('postLogin')}}', { _token: $_token, txtUsername: $email , txtPassword: $pass ,remember: $remember,source: $source}, function(data)
        {
            if(data == 1)
            {
                window.location.replace('{{URL::Route('home')}}');
            }
            else
            {
                $('#mdl_msg').text(data.message);
                $('#myModal').modal('show');
            }
            //console.log(data);
        });
    }

    
</script>
</body>
</html>
