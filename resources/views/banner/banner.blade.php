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
        Banner
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Banner</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Banner 1</h3>
            </div>
            <form role="form" method="post" action="{{URL::Route('postBanner',1)}}" enctype ="multipart/form-data">
              <div class="box-body">
                <!--<div class="form-group">
                  <label for="h1">h1</label>
                  <input type="text" class="form-control" id="h1" name="h1" placeholder="Enter h1" >
                </div>
                <div class="form-group">
                  <label for="span">span</label>
                  <input maxlength="4" type="text" class="form-control" id="span" name="span" placeholder="span" >
                </div>-->
                <div class="form-group">
                  <label for="h2">Message 1</label>
                  <textarea type="text" style="resize: none;" rows="3" class="form-control" id="h2" name="h2" value="" placeholder="h2" >{{$banner1['h2']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="h4">Message 2</label>
                  <textarea type="text" style="resize: none;" rows="3"  class="form-control" id="h4" name="h4" placeholder="h4" value="">{{$banner1['h4']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="file">Banner 1 image</label>
                  <input type="file" id="file" name="image">
                </div>
                <img alt="no image" style="width:250px;height:200px;padding:5px" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$banner1['thumbnail']}}">
               
              </div>
              <div class="box-footer">
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Banner 2</h3>
            </div>
            <form role="form" method="post" action="{{URL::Route('postBanner',2)}}" enctype ="multipart/form-data">
              <div class="box-body">
                <!--<div class="form-group">
                  <label for="h1">h1</label>
                  <input type="text" class="form-control" id="h1" name="h1" placeholder="Enter h1" >
                </div>
                <div class="form-group">
                  <label for="span">span</label>
                  <input maxlength="4" type="text" class="form-control" id="span" name="span" placeholder="span" >
                </div>-->
                <div class="form-group">
                  <label for="h2">Message 1</label>
                  <textarea type="text" style="resize: none;" rows="3" class="form-control" id="h2" name="h2" value="" placeholder="h2" >{{$banner2['h2']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="h4">Message 2</label>
                  <textarea type="text" style="resize: none;" rows="3"  class="form-control" id="h4" name="h4" placeholder="h4" value="">{{$banner2['h4']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="file">Banner 2 image</label>
                  <input type="file" id="file" name="image">
                </div>
                <img   alt="no image" style="width:250px;height:200px;padding:5px" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$banner2['thumbnail']}}">
              </div>
              <div class="box-footer">
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Banner 3</h3>
            </div>
            <form role="form" method="post" action="{{URL::Route('postBanner',3)}}" enctype ="multipart/form-data">
              <div class="box-body">
                <!--<div class="form-group">
                  <label for="h1">h1</label>
                  <input type="text" class="form-control" id="h1" name="h1" placeholder="Enter h1" >
                </div>
                <div class="form-group">
                  <label for="span">span</label>
                  <input maxlength="4" type="text" class="form-control" id="span" name="span" placeholder="span" >
                </div>-->
                <div class="form-group">
                  <label for="h2">Message 1</label>
                  <textarea type="text" style="resize: none;" rows="3" class="form-control" id="h2" name="h2" value="" placeholder="h2" >{{$banner3['h2']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="h4">Message 2</label>
                  <textarea type="text" style="resize: none;" rows="3"  class="form-control" id="h4" name="h4" placeholder="h4" value="">{{$banner3['h4']}}</textarea>
                </div>
                <div class="form-group">
                  <label for="file">Banner 3 image</label>
                  <input type="file" id="file" name="image">
                </div>
                <img alt="no image" style="width:250px;height:200px;padding:5px" src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$banner3['thumbnail']}}">
              </div>
              <div class="box-footer">
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
@endsection

