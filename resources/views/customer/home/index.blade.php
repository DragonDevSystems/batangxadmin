@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
		@include('customer.includes.mainMenu')
		<div class="header_slide">
			@include('customer.includes.categories')
			@include('customer.includes.productSlider')
			<div class="clear"></div>
		</div>
	</div>
	<div class="main">
		<div class="content">
			@include('customer.includes.newProduct')
			@include('customer.includes.featureProduct')
		</div>
	</div>
</div>
	@include('customer.includes.footer')
@endsection