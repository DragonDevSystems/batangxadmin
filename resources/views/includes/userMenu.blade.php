<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="{{env('FILE_PATH_CUSTOM').''.$userInfo['userDp']}}" class="user-image" alt="User Image">
    <span class="hidden-xs">{{$userInfo['fname']}} {{$userInfo['lname']}}</span>
  </a>
  <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
      <img src="{{env('FILE_PATH_CUSTOM').''.$userInfo['userDp']}}" class="img-circle" alt="User Image">
      <p>
        {{$userInfo['fname']}} {{$userInfo['lname']}}- Web Developer
        <small>Member since Nov. 2012</small>
      </p>
    </li>
    <!-- Menu Body -->
    <li class="user-body">
      <div class="row">
        <div class="col-xs-4 text-center">
          <a href="#">Followers</a>
        </div>
        <div class="col-xs-4 text-center">
          <a href="#">Sales</a>
        </div>
        <div class="col-xs-4 text-center">
          <a href="#">Friends</a>
        </div>
      </div>
      <!-- /.row -->
    </li>
    <!-- Menu Footer-->
    <li class="user-footer">
      <div class="pull-left">
        <a href="#" class="btn btn-default btn-flat">Profile</a>
      </div>
      <div class="pull-right">
        <a href="{{ URL::Route('getLogout') }}" class="btn btn-default btn-flat">Sign out</a>
      </div>
    </li>
  </ul>
</li>