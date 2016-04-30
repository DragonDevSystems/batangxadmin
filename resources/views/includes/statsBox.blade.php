<!-- Small boxes (Stat box) -->
      <div class="row stats_box"></div>
      <!-- /.row -->
      <script type="text/javascript">
        function countstatsbox()
        {
          $.get('{{URL::Route('statsbox')}}', function(data)
          {
            if(data.length != 0)
            {
               $('.stats_box').empty();
              for(var i = 0 ; i < data.length ; i++)
              {
                $('.stats_box').append($('<div />' , { 'class' : 'col-lg-3 col-xs-6'}).append(
                                          $('<div />' , { 'class' : 'small-box '+data[i].bg_color}).append(
                                            $('<div />' , { 'class' : 'inner'}).append(
                                            '<h3>'+data[i].count+'</h3>\
                                              <p>'+data[i].content_title+'</p>'),
                                             $('<div />' , { 'class' : 'icon'}).append(
                                              $('<i />' , {'class' : 'ion '+data[i].Ionicons})),
                                             $('<a />' , { 'href' : data[i].link , 'class' : 'small-box-footer' , 'html' : 'More info <i class="fa fa-arrow-circle-right"></i>'}))));
              }
            }
           
          });
        }
        countstatsbox();
        window.setInterval(function () {countstatsbox()}, 5000);
    </script>