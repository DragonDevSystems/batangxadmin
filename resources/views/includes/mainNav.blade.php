  <!-- Control Sidebar Toggle Button -->
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{env('FILE_PATH_CUSTOM').''.$userInfo['userDp']}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{$userInfo['fname']}} {{$userInfo['lname']}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview {{ ($mt == "db") ? "active" : ""}}">
          <a href="{{ URL::Route('home') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa pull-right"></i>
          </a>
        </li> 
        <li class="treeview {{ ($mt == "uam") ? "active" : ""}}">
          <a href="{{ URL::Route('home') }}">
            <i class="fa fa-users"></i> <span>User Access Management</span> <i class="fa pull-right"></i>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $mt == "uam" ? (($cc == "ual") ? "active" : "") : ""}}"><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-users"></i> User Access Level</a></li>
          </ul>
        </li>
        <!--end filemaintenance part -->
        <li class="treeview">
          <a href="{{ URL::Route('home') }}">
            <i class="fa fa-users"></i> <span>Customer Support</span> <i class="fa pull-right"></i>
          </a>
        </li>
        <li class="treeview">
          <a href="{{ URL::Route('home') }}">
            <i class="fa fa-gears"></i> <span>Accounts Management</span> <i class="fa pull-right"></i>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>