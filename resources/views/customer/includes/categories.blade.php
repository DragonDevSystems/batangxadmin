<?php $categories = App::make("App\Http\Controllers\GlobalController")->categoryList(); ?>
<div class="header_bottom_left">				
	<div class="categories">
	<ul>
		<h3>Categories</h3>
		@foreach($categories as $category)
			<li><a href="#">{{$category['name']}}</a></li>
		@endforeach
	</ul>
	</div>					
</div>