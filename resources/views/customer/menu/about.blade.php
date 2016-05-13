@extends('customer.layouts.master')
@section('addHead')
  <title>About | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
		@include('customer.includes.mainMenu')
	</div>
 <div class="main">
    <div class="content">
    	<div class="section group">
    		<?php 
    			$aboutInfo = App::make("App\Http\Controllers\AboutController")->getAboutInformation();
    			$locations = App::make("App\Http\Controllers\AboutController")->getAllLocation();
    		 ?>
			<div class="col_1_of_3 span_2_of_3">
				<h3>{{$aboutInfo['title']}}</h3>
				<img src="{{env('FILE_PATH_CUSTOM')}}img/{{$aboutInfo['thumbnail']}}" alt="no image">
				<p>{!! str_replace("\n","<br>", $aboutInfo->content) !!}</p>
			</div>
			<div class="col_1_of_3 span_1_of_3">
				<h3>Our stores are conveniently located in:</h3>
				@if(count($locations) != 0)
					@foreach($locations as $loc)
						<div class="history-desc">
							<p class="history">{{$loc['location']}}</p>
						 	<div class="clear"></div>
						</div>
					@endforeach
				@endif
			</div>
		</div>	
		@if(count($testimonial)!= 0)
		<div id="block_body" style="background:url({{env('FILE_PATH_CUSTOM')}}img/bg.gif)">
			<div id="block" style="background:url({{env('FILE_PATH_CUSTOM')}}img/bg.gif)">
				<h3 stle="background:url({{env('FILE_PATH_CUSTOM')}}img/h3.png)">Random Testimonial</h3>
				<div class="photo" style="background:url({{env('FILE_PATH_CUSTOM')}}img/photo-bg.png)">
					<img src="{{env('FILE_PATH_CUSTOM')}}img/photo-bg.png" alt="" class="photo-bg"/>
					<img src="{{env('FILE_PATH_CUSTOM')}}img/photo.jpg" alt="" class="photo" />
				</div>
				<p class="content" style="background:url({{env('FILE_PATH_CUSTOM')}}img/bg.gif)">
					<span class="laquo" style="background:url({{env('FILE_PATH_CUSTOM')}}img/laquo.png)">&nbsp;</span>
					{{$testimonial['message']}}
					<span class="raquo" style="background:url({{env('FILE_PATH_CUSTOM')}}img/raquo.png)">&nbsp;</span>
				</p>
				<div class="sign" style="background:url({{env('FILE_PATH_CUSTOM')}}img/bg.gif)">
					<a href="javascript:void(0)">{{$testiInfo['fname']}} {{$testiInfo['lname']}}</a>
				</div>
			</div>
		</div>
		@endif
    </div>
 </div>
</div>
	@include('customer.includes.footer')
<script type="text/javascript">
	$('#btn_login').click(function()
		{
			$('#mdl_login').modal('show');
		}
	);
	$('#btn_registration').click(function()
		{
			$('#mdl_registration').modal('show');
		}
	);
	
</script>
@endsection