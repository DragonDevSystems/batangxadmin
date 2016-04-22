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
        Delivery List
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Delivery</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div-entry" class="box box-success">
		</div>

		<!-- product list -->
		<div class="box box-primary">
            <div class="box-header">
            	<h3 class="box-title">Delivery List</h3>
            	<div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" data-placeid="" onClick="setNewEntry();">
                    <i class="fa fa-plus"></i>
                    Add Delivery
                  </button>
                  <button id="editProduct" class="btn btn-info btn-sm " type="button" disabled>
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="product_list" class="table table-bordered table-hover">
                <thead>
                <?php 
                    $headers = Schema::getColumnListing('prod_information');
                    $include = ["id","name","description"]; 
                ?>
                <tr>
                  @foreach($headers as $header)
                    @if(in_array($header, $include))
                       <th>{{$header}}</th>
                    @endif
                  @endforeach
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
        </div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
</script>
@endsection

