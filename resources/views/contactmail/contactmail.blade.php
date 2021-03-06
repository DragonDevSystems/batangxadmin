@extends('layouts.master')
@section('addHead')
  <title>Dashboard</title>
@endsection

@section('content')
<div class="wrapper">
@include('includes.header')
  @include('includes.mainNav')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inquiries
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inquiries</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="row">
         @include('includes.mailNav')
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm trash_mail"><i class="fa fa-trash-o"></i></button>
                </div>
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  @foreach($ContactUs as $mail)
                  <?php $time = \Carbon\Carbon::createFromTimeStamp(strtotime($mail['created_at']))->diffForHumans(); 
                    $read = $mail['read'] == 0 ? "text-yellow" : "";
                  ?>
                   <tr>
                    <td><input type="checkbox" data-id="{{$mail['id']}}"></td>
                    <td class="mailbox-star"><a href="javascript:void(0)">
                      <i class="fa fa-star {{$read}}"></i>
                    </a></td>
                    <td class="mailbox-name"><a href="{{ URL::Route('getReadMailView',[$mm,$mail['id']]) }}">{{$mail['name']}}</a></td>
                    <td class="mailbox-subject"><b>{{$mail['company']}}</b> - {{str_limit($mail['message'], $limit = 60, $end = '...')}}
                    </td>
                    <td class="mailbox-attachment"></td>
                    <td class="mailbox-date">{{$time}}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>

            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <div class="pull-right">
                	{!! $ContactUs->render() !!}
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
  $(document).on("click",".trash_mail",function(){
    var type = "{{$mm}}";
    if($('.mailbox-messages input[type="checkbox"]:checked').length != 0){
      if(type == "inbox"){
        promptConfirmation("Do you want to move this mail to trash?");
        $('#btnYes').click(function() {
          $('.mailbox-messages input[type="checkbox"]:checked').each(function () {
            var id = $(this).data('id');
            var _token = "{{ csrf_token() }}";
            $.post('{{URL::Route('moveToTrash')}}',{ _token: _token, id: id } , function(response)
            {
              if(response.status == "success"){
               promptMsg(response.status,response.message);
              }
            });
            $(this).closest('tr').fadeOut("slow",function(){ 
                                        $(this).remove(); 
                                      });
          });
        });
      }
      else{
        promptConfirmation("Do you want to remove this mail permanently?");
        $('#btnYes').click(function() {
          $('.mailbox-messages input[type="checkbox"]:checked').each(function () {
            var id = $(this).data('id');
            var _token = "{{ csrf_token() }}";
            $.post('{{URL::Route('deleteMail')}}',{ _token: _token, id: id } , function(response)
            {
              if(response.status == "success"){
               promptMsg(response.status,response.message);
              }
            });
            $(this).closest('tr').fadeOut("slow",function(){ 
                                        $(this).remove(); 
                                      });
          });
        });
      }
     
    }
    /*$id = [];
    $i = 0;
    $('.mailbox-messages input[type="checkbox"]:checked').each(function () {
      if (this.checked) {
        $id[$i] = $(this).data('id'); 
      }
      $i++;
    });
    var _token = "{{ csrf_token() }}";
    if($id.length != 0){
      promptConfirmation("Do you want to move this mail to trash?");
      $('#btnYes').click(function() {
        $.post('{{URL::Route('moveToTrash')}}',{ _token: _token, id: $id } , function(response)
        {
          console.log(response);
          if(response.status == "success"){
            promptMsg(response.status,response.message);
            $('.mailbox-messages input[type="checkbox"]:checked').each(function () {
              $(this).closest('tr').fadeOut("slow",function(){ 
                                      $(this).remove(); 
                                    });
            });
          }
        });
      });
    }
    else{
      promptMsg("fail","Please choose email.")
    }*/
  });
	$(document).ready(function() {
		$('.mailbox-messages input[type="checkbox"]').iCheck({
	      checkboxClass: 'icheckbox_flat-blue',
	      radioClass: 'iradio_flat-blue'
	    });
	    //Enable check and uncheck all functionality
	    $(".checkbox-toggle").click(function () {
	      var clicks = $(this).data('clicks');
	      if (clicks) {
	        //Uncheck all checkboxes
	        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
	        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
	      } else {
	        //Check all checkboxes
	        $(".mailbox-messages input[type='checkbox']").iCheck("check");
	        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
	      }
	      $(this).data("clicks", !clicks);
	    });
	     //Handle starring for glyphicon and font awesome
	    $(".mailbox-star").click(function (e) {
	      e.preventDefault();
	      //detect type
	      var $this = $(this).find("a > i");
	      var glyph = $this.hasClass("glyphicon");
	      var fa = $this.hasClass("fa");

	      //Switch states
	      if (glyph) {
	        $this.toggleClass("glyphicon-star");
	        $this.toggleClass("glyphicon-star-empty");
	      }

	      if (fa) {
	        $this.toggleClass("fa-star");
	        $this.toggleClass("fa-star-o");
	      }
	    });
	});
</script>
@endsection

