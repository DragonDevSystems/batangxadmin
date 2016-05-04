<div class="header_bottom">
 	<div class="menu">
 		<ul>
	    	<li class="{{ $mt == "home" ? "active" : ""}}"><a href="{{ URL::Route('cusIndex') }}">Home</a></li>
	    	<li class="{{ $mt == "about" ? "active" : ""}}"><a href="{{ URL::Route('getAbout') }}">About</a></li>
	    	<li class="{{ $mt == "news" ? "active" : ""}}"><a href="{{ URL::Route('getNews') }}">News</a></li>
	    	<li class="{{ $mt == "contactus" ? "active" : ""}}"><a href="{{ URL::Route('getContactUs') }}">Contact</a></li>
	    	<div class="clear"></div>
			</ul>
 	</div>
 	<!--
 	<div class="search_box">
 		<form>
 			<input type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}"><input type="submit" value="">
 		</form>
 	</div>-->
 	<div class="clear"></div>
 </div>	   