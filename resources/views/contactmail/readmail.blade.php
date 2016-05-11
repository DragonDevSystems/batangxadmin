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
        Contact Mail
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Contact Mail</li>
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
              <h3 class="box-title">Read Mail</h3>
              @if($count > 1)
              <div class="box-tools pull-right">
                <a href="javascript:void(0)" class="btn btn-box-tool next" data-type="prev" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                <a href="javascript:void(0)" class="btn btn-box-tool next" data-type="next" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
              </div>
              @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h5>From: {{$mail['email']}}
                   <?php $time = \Carbon\Carbon::createFromTimeStamp(strtotime($mail['created_at']))->toDayDateTimeString(); 
                    $read = $mail['read'] == 1 ? "text-yellow" : "";
                  ?>
                  <span class="mailbox-read-time pull-right">{{$time}}</span></h5>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <p>{!! str_replace("\n","<br>", $mail->message) !!}
                </p>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
            <div class="box-footer">
              <button type="button" class="btn btn-default trash_mail" data-id="{{$mail['id']}}"><i class="fa fa-trash-o"></i> Delete</button>
            </div>
            <!-- /.box-footer -->
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
	/*$(document).on("click",".trash_mail",function(){
    var id = $(this).data('id');
    var _token = "{{ csrf_token() }}";
    promptConfirmation("Do you want to move this mail to trash?");
    $('#btnYes').click(function() {
      
      $.post('{{URL::Route('moveToTrash')}}',{ _token: _token, id: id } , function(response)
      {
        console.log(response);
        if(response.status == "success"){
          promptMsg(response.status,response.message);
          window.location.href = "{{URL::Route('getContactMailView')}}";
        }
      });
    });
  });*/
  $(document).on("click",".trash_mail",function(){
    var type = "{{$mm}}";
    var id = $(this).data('id');
    if(type == "inbox"){
      promptConfirmation("Do you want to move this mail to trash?");
      $('#btnYes').click(function() {
       
        var _token = "{{ csrf_token() }}";
        $.post('{{URL::Route('moveToTrash')}}',{ _token: _token, id: id } , function(response)
        {
          if(response.status == "success"){
           promptMsg(response.status,response.message);
           window.location.href = "{{URL::Route('getContactMailView')}}";
          }
        });
        $(this).closest('tr').fadeOut("slow",function(){ 
                                    $(this).remove(); 
                                  });
      });
    }
    else{
      promptConfirmation("Do you want to remove this mail permanently?");
      $('#btnYes').click(function() {
        var _token = "{{ csrf_token() }}";
        $.post('{{URL::Route('deleteMail')}}',{ _token: _token, id: id } , function(response)
        {
          if(response.status == "success"){
           promptMsg(response.status,response.message);
           window.location.href = "{{URL::Route('getTrashMailView')}}";
          }
        });
        $(this).closest('tr').fadeOut("slow",function(){ 
                                    $(this).remove(); 
                                  });
      });
    }
  });
  $(document).on("click",".next",function(){
    var id = "{{$mail['id']}}";
    var type_for = "{{$mm}}";
    var type = $(this).data('type');
    $.get('{{URL::Route('getNextMail')}}',{  id: id , type : type , type_for : type_for} , function(response)
    {
      window.location.href = response.url;
    });
  });
</script>
@endsection

