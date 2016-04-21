<?php $categories = App::make("App\Http\Controllers\GlobalController")->categoryList(); ?>
<div class="rightsidebar span_3_of_1">
	<h2>CATEGORIES</h2>
	<ul>
		@foreach($categories as $category)
			<li><a href="{{ URL::Route('getProByCat',$category['name']) }}">{{$category['name']}}</a></li>
		@endforeach
	</ul>
</div>