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
        About
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascrivt:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">About</li>
      </ol>
    </section>
    <section class="content">
       <div class="row">
        <div class="col-md-5">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Information</h3>
            </div>
            <form role="form" method="post" action="{{URL::Route('addAboutInfo')}}" enctype ="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" value="{{$about['title']}}" class="form-control" id="title" name="title" value="" placeholder="Enter title" >
                </div>
                <div class="form-group">
                  <label for="content">Content</label>
                  <textarea type="text"style="resize: none;" rows="6" class="form-control" id="content" name="content" value="" placeholder="Enter content" >{{$about['content']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" value="{{$about['email']}}" class="form-control" id="email" name="email" value="" placeholder="Enter email" >
                </div>
                <div class="form-group">
                  <label>Mobile No:</label>
                    <input type="text" value="{{$about['number']}}" value=""class="form-control" placeholder="Mobile or Phone Number" name="mobile" id="mobile" data-inputmask='"mask": "(99) 999-9999"' data-mask required>
                </div>
                <div class="form-group">
                  <label for="email">Facebook link</label>
                  <input type="text" value="{{$about['fb_link']}}" class="form-control" id="facebook" name="facebook" value="" placeholder="Enter facebook" >
                </div>
                <div class="form-group">
                  <label for="email">Ebay link</label>
                  <input type="text" value="{{$about['ebay_link']}}" class="form-control" id="ebay" name="ebay" value="" placeholder="Enter ebay" >
                </div>
                <div class="form-group">
                  <label for="file">Information image</label>
                  <input type="file" id="file" name="image">
                </div>
                <img alt="no image" style="width:250px;height:200px;padding:5px" src="{{env('FILE_PATH_CUSTOM')}}img/{{$about['thumbnail']}}">
              </div>
              <div class="box-footer">
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
         <div class="col-md-7">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Location</h3>
            </div>
            <form role="form" method="post" action="" enctype ="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="loc_title">Input Address</label>
                  <div class="input-group">
                    <input type="text" value="" class="form-control" id="location" name="location">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-success add">ADD</button>
                    </div>
                  </div>
                </div>
                <div id="location_list">
                  <label for="loc_title">Location List</label>
                  @if(count($location)!= 0)
                    @foreach($location as $i)
                    <div class="form-group">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" value="{{$i['location']}}" disabled>
                        <div class="input-group-btn">
                          <button type="button" class="btn btn-danger remove" data-id="{{$i['id']}}">Remove</button>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
  $(function () {
    $("[data-mask]").inputmask();
  });
  $(document).on("click",".add",function(){
    var address = $('#location').val();
    if($.trim( $('#location').val() ) != '' ){
      promptConfirmation("Are you sure you want to add this record?");
      $('#btnYes').click(function() {
        var _token = "{{ csrf_token() }}";
        $.post('{{URL::Route('addAddress')}}',{ _token: _token ,address: address} , function(response)
        {
          if(response.status == "success"){
              promptMsg(response.status,response.message);
              $('#location_list').append('<div class="form-group">\
                <div class="input-group input-group-sm">\
                  <input type="text" class="form-control" value="'+response.location+'" disabled>\
                  <div class="input-group-btn">\
                    <button type="button" class="btn btn-danger" data-id="'+response.id+'">Remove</button>\
                  </div>\
                </div>\
              </div>');
              $('#location').val("");
          }
        });
      });

    }
    else{
      $('#location').focus();
    }
  });
  $(document).on("click",".remove",function(){
    var id = $(this).data('id');
    var _token = "{{ csrf_token() }}";
    $this = $(this);
    promptConfirmation("Are you sure you want to delete this record?");
    $('#btnYes').click(function() {
      $.post('{{URL::Route('deleteAddress')}}',{ _token: _token ,id: id} , function(response)
      {
        if(response.status == "success"){
          promptMsg(response.status,response.message);
          $this.closest(".form-group").fadeOut("slow",function(){ 
                                                              $(this).remove(); 
                                                            });
        }
      });
    });
  });
</script>
@endsection

