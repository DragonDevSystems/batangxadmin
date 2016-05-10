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
          <big class="pull-center">Sales Report</big>
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
            <th>Invoice No.</th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
        	@for($x=0 ; $x < count($products) ; $x++)
	          <tr>
	            <td>{{$products[$x]['date']}}</td>
	            <td>{{$products[$x]['inv_no']}}</td>
	            <td>{{$products[$x]['product']}}</td>
	            <td>{{$products[$x]['unit_price']}}</td>
	            <td>{{$products[$x]['qty']}}</td>
	            <td>{{$products[$x]['subtotal']}}</td>
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
            <th style="width:50%">Total Sales:</th>
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