@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <img width="150" height="51" src="{{env('FILE_PATH_CUSTOM')}}img/gamextreme.png" alt="" />
        <small class="pull-right">Date: {{$invoiceDate}}</small>
      </h2>
    </div>
    <!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      From
      <address>
      <strong>GameXtreme</strong><br>
        Alabang Town Center,<br>
        Muntinlupa City, Metro Manila<br>
        Phone: (02) 842-2782<br>
        Email: allenjamesxxiv@gmail.com
      </address>
    </div>
  <!-- /.col -->
  <div class="col-sm-4 invoice-col">
    To
    <address>
      <strong>{{$userInfo['fname']}} {{$userInfo['lname']}}</strong><br>
      Phone: {{$userInfo['mobile']}}<br>
      Email: {{$userInfo['email']}}
    </address>
  </div>
  <!-- /.col -->
  <div class="col-sm-4 invoice-col">
    <b>Invoice # {{$invoiceNum}}</b><br>
    <br>
    <b>Account No.:</b> {{$accountNum}}
  </div>
  <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Table row -->
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Qty</th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          
          @if($onCartList[0]['totalQty'] != 0)
            @for($x=0 ; $x < count($onCartList[0]['productInfo']) ; $x++)
              <tr>
                <td>{{$onCartList[0]['productInfo'][$x]['qty']}}</td>
                <td>{{$onCartList[0]['productInfo'][$x]['name']}}</td>
                <td>{{$onCartList[0]['productInfo'][$x]['unit_price']}}</td>
                <td>{{$onCartList[0]['productInfo'][$x]['price']}}</td>
              </tr>
            @endfor
          @endif

        </tbody>
      </table>
    </div>
  <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row">
    <!-- accepted payments column -->
    <div class="col-xs-6">
    <p class="lead">Payment Methods:</p>
      <img src="{{env('FILE_PATH_CUSTOM')}}img/paypal2.png" alt="Paypal">
      <img width="51" height="32" src="{{env('FILE_PATH_CUSTOM')}}img/cashpickup.jpg" alt="Cash Pick Up">

      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
      Note:
      To pick up your product please bring valid I.D. and a copy of you invoice.Thank you.
      <br><br>
      For "Cash on Pick Up" trasaction, your invoice trasaction will be forfieted for next 24hrs.
      </p>
    </div>
    <!-- /.col -->
    <div class="col-xs-6">
      <!--<p class="lead">Amount Due 2/22/2014</p>-->
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th>No. Items:</th>
            <td>{{$onCartList[0]['totalQty']}}</td>
          </tr>
          <tr>
            <th style="width:50%">Subtotal:</th>
            <td>{{$onCartList[0]['totalPrice']}}</td>
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