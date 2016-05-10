@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <div class="col-xs-4">
        <h2 class="page-header">
          <small>Date: 5-10-2016</small>
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
          <tr>
            <td>5-10-2016</td>
            <td>001</td>
            <td>LG G4</td>
            <td>5.00</td>
            <td>5</td>
            <td>25.00</td>
          </tr>
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
            <td>PHP 5,000.00</td>
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