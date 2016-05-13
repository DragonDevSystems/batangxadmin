@extends('customer.layouts.master')
@section('addHead')
  <title>Reports | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <div class="col-xs-4">
        <h2 class="page-header">
          <small>Date: {{$dateprint}}</small>
        </h2>
      </div>
      <div class="col-xs-4">
        <h2 class="page-header">
          <big class="pull-center">Delivery Report</big>
        </h2>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- Table row -->
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
          	<th>Date</th>
            <th>Receipt No.</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
        	@for($x=0 ; $x < count($receipts) ; $x++)
	          <tr>
	            <td>{{$receipts[$x]['date']}}</td>
	            <td>{{$receipts[$x]['receipts']}}</td>
	            <td>{{$receipts[$x]['remarks']}}</td>
	          </tr>
          	@endfor
        </tbody>
      </table>
    </div>
  <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row">
    <!-- /.col -->
    <div class="col-xs-6">
      <div class="table-responsive  pull-rigth">
        <table class="table">
          <tr>
            <th style="width:50%">Total Delivery:</th>
            <td>{{$allTotal}}</td>
          </tr>
          <tr>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</div>
@endsection