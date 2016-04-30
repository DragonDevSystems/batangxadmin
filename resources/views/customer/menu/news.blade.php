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
    	@foreach($news as $new)
    		<?php $time = \Carbon\Carbon::createFromTimeStamp(strtotime($new['created_at']))->toDayDateTimeString();
            ?>
	    	<div class="image group">
				<div class="grid images_3_of_1">
					<img src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/{{$new['img_thumbnail']}}" alt="" />
				</div>
				<div class="grid news_desc">
					<h3>{{$new['title']}}</h3>
					<h4>Posted on {{$time}} by <span><a href="#">Game Extreme</a></span></h4>
					<p>{{$new['message']}}<!--<a title="more" href="javascript:void(0)">[....]</a>--></p>
				</div>
		    </div>	
		@endforeach
		<div class="content-pagenation">
			{!! $news->render() !!}
		</div>	
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