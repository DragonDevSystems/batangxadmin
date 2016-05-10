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
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview {{ ($mt == "db") ? "active" : ""}}">
          <a href="{{ URL::Route('home') }}">
            <!--<i class="fa fa-dashboard"></i>--> <span>Dashboard</span> <i class="fa pull-right"></i>
          </a>
        </li> 
        <li class="treeview {{ ($mt == "uam") ? "active" : ""}}">
          <a href="{{ URL::Route('home') }}">
            <!--<i class="fa fa-users"></i>--> <span>User Access Management</span> <i class="fa pull-right"></i>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $mt == "uam" ? (($cc == "ual") ? "active" : "") : ""}}"><a href="{{ URL::Route('getUAL') }}"><!--<i class="fa fa-users"></i>--> User Admin Level</a></li>
            <li class="{{ $mt == "uam" ? (($cc == "cl") ? "active" : "") : ""}}"><a href="{{ URL::Route('getCustomer') }}"><!--<i class="fa fa-users"></i>--> Customer List</a></li>
          </ul>
        </li>

        <li class="{{ ($mt == "fm") ? "active" : ""}} treeview">
          <a href="#">
            <!--<i class="fa fa-files-o"></i>--> <span>File Maintenance</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $mt == "fm" ? (($cc == "pc") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getCategory') }}">
                <!--<i class="fa fa-globe"></i>--> Product Category
              </a>
            </li>
            <li class="{{ $mt == "fm" ? (($cc == "pt") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getProductView') }}">
                <!--<i class="fa fa-gamepad"></i>--> <span>Products</span> <i class="fa pull-right"></i>
              </a>
            </li>  
            <li class="{{ $mt == "fm" ? (($cc == "dl") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getDeliveryView') }}">
                <!--<i class="fa fa-truck"></i>--> <span>Delivery</span> <i class="fa pull-right"></i>
              </a>
            </li>
            <li class="{{ $mt == "fm" ? (($cc == "bn") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getBannerView') }}">
                <!--<i class="fa fa-truck"></i>--> <span>Banner</span> <i class="fa pull-right"></i>
              </a>
            </li>
          </ul>
        </li>

        <!--end filemaintenance part -->
        
        <li class="{{ ($mt == "tp") ? "active" : ""}} treeview">
          <a href="#">
            <!--<i class="fa fa-files-o"></i>--> <span>Transactions</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="treeview {{ $mt == "tp" ? (($cc == "inv") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getInvoiceView') }}">
                <!--<i class="fa fa-file-text-o"></i>--> <span>Invoices</span> <i class="fa pull-right"></i>
              </a>
            </li>

            <li class="treeview {{ $mt == "tp" ? (($cc == "th") ? "active" : "") : ""}}">
              <a href="{{ URL::Route('getTransactionHistory') }}">
                <!--<i class="fa fa-file-text-o"></i>--> <span>Transaction History</span> <i class="fa pull-right"></i>
              </a>
            </li>
            <li class="{{ $mt == "tp" ? (($cc == "wi") ? "active" : "") : ""}} treeview">
              <a href="{{ URL::Route('getWalkIn') }}">
                <!--<i class="fa fa-money"></i>--> <span>Walk-in</span> <i class="fa pull-right"></i>
              </a>
            </li>
          </ul>
        </li>
        <li class="treeview {{ ($mt == "ml") ? "active" : ""}}">
          <a href="{{ URL::Route('getContactMailView') }}">
            <!--<i class="fa fa-envelope-o"></i>--> <span>Inquiries</span> <i class="fa pull-right"></i>
          </a>
        </li>

        <li class="treeview {{ ($mt == "int") ? "active" : ""}}">
          <a href="{{ URL::Route('getInventoryView') }}">
            <!--<i class="fa fa-file-text-o"></i>--> <span>Inventory</span> <i class="fa pull-right"></i>
          </a>
        </li>
        <li class="treeview {{ ($mt == "cl") ? "active" : ""}}">
          <a href="{{ URL::Route('getCriLvlView') }}">
            <!--<i class="fa fa-file-text-o"></i>--> <span>Critical Level Item</span> <i class="fa pull-right"></i>
          </a>
        </li>
        <li class="treeview {{ ($mt == "sr") ? "active" : ""}}">
          <a href="{{ URL::Route('getSales') }}">
            <!--<i class="fa fa-file-text-o"></i>--> <span>Sales Report</span> <i class="fa pull-right"></i>
          </a>
        </li>

        <li class="treeview {{ ($mt == "nw") ? "active" : ""}}">
          <a href="{{ URL::Route('getNewsView') }}">
            <!--<i class="fa fa-newspaper-o"></i>--> <span>News</span> <i class="fa pull-right"></i>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>