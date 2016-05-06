<li>
  <a href="#" id="date_time"></a>
  <script type="text/javascript">window.onload = date_time('date_time');</script>
</li>
<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
  <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
    <img src="{{env('FILE_PATH_CUSTOM').''.$userInfo['userDp']}}" class="user-image" alt="User Image">
    <span class="hidden-xs">{{$userInfo['fname']}} {{$userInfo['lname']}}</span>
  </a>
  <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
        <img src="{{env('FILE_PATH_CUSTOM').''.$userInfo['userDp']}}" class="img-circle" alt="User Image">
      <p>
        {{$userInfo['fname']}} {{$userInfo['lname']}}
        <small>Member since {{date("M Y", strtotime($userInfo['dm']))}}</small>
      </p>
    </li>
    <!-- Menu Footer-->
    <li class="user-footer">
      <div class="pull-left">
        <a href="javascript:void(0)" id="CPP" class="btn btn-default btn-flat">Change Image</a>
      </div>
      <div class="pull-right">
        <a href="{{ URL::Route('getLogout') }}" class="btn btn-default btn-flat">Sign out</a>
      </div>
    </li>
  </ul>
</li>
<form id="uploadProfilePic" method="post" enctype ="multipart/form-data">
    <input type="file" name="input_profile_image" id="input_profile_image" style="display:none">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <button id="submit_profile_pic" type="submit" style="display:none"></button>
</form>
<script type="text/javascript">
  $(document).ready(function() {
    var form = document.getElementById('uploadProfilePic');
    var request = new XMLHttpRequest();
    form.addEventListener('submit',function(e){
      e.preventDefault();
      var formdata = new FormData(form);
      request.open('post','{{ URL::Route('uploadProfilePicture')}}');
      request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
              arr = JSON.parse(request.responseText);
              $('.user-menu').find('img').attr("src","{{env('FILE_PATH_CUSTOM')}}"+arr.image);
              $('.main-sidebar').find('.image').find('img').attr("src","{{env('FILE_PATH_CUSTOM')}}"+arr.image);
            }
        };
      request.send(formdata);
    });
    request.upload.addEventListener("progress", uploadProgressFile, false);
    request.addEventListener('load',transferCompleteFile);
    function transferCompleteFile(evt) {
      console.log("The transfer file  is complete.");
      $(".loadmodal").fadeOut("slow",function(){ 
                                                  $(this).modal("hide"); 
                                                });
      promptMsg('success','Upload success.');
    }
    function uploadProgressFile(evt) {
        if (evt.lengthComputable) {
            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
            $(".progress").find('.progress-bar').css("width",percentComplete+"%").html(percentComplete+"% Complete (uploading ...)");
            if(percentComplete == 100){
              $(".progress").find('.progress-bar').css("width",percentComplete+"%").html(percentComplete+"% Complete (complete ...)");
            }
        }
        else {
          alert('cant upload.')
        }
      }
  });
  $(document).on("click","#CPP",function(){
   $('#input_profile_image').click();
  });
  $(document).on("change","#input_profile_image",function(){
    var chk = $(this).val();
    if (chk.match(/(?:gif|jpg|png|bmp)$/)) {
      //$(".loadmodal").modal("show");
      loadingModal();
      $('#submit_profile_pic').click();
    }
    else{
      promptMsg('fail','PLease choose image.');
    }
  });
</script>