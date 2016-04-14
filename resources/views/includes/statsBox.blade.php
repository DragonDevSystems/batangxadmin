<!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="nu">0</h3>

              <p>New Users This Month</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="ou">0</h3>

              <p>Online User</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id="ru">0</h3>

              <p>Registered User</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id="uvu">0</h3>

              <p>Unverified User</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <script type="text/javascript">
        function countstatsbox()
        {
          $.get('{{URL::Route('statsbox')}}', function(data)
          {
            $("#nu").text(data.nu);
            $("#ou").text(data.ou);
            $("#ru").text(data.ru);
            $("#uvu").text(data.uvu);
          });
        }
        countstatsbox();
        window.setInterval(function () {countstatsbox()}, 5000);
    </script>