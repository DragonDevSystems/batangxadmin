@extends('layouts.master')
@section('addHead')
  <title>Audit Trail</title>
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
       Audit Trail
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascrivt:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Audit Trail</li>
      </ol>
    </section>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Audit Trail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="auditTrailList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>User</th>
                  <th>Details</th>
                  <th>Ip Address</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $i)
                <tr>
                  <td>{{$i['name']}}</td>
                  <td>{{$i['task']}}</td>
                  <td>{{$i['ip']}}</td>
                  <td>{{$i['date']}}</td>
                </tr>
                @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script>
  $(function () {
    $("#auditTrailList").DataTable();
  });
</script>
@endsection

