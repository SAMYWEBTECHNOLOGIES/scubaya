@php
   $gallery_images = json_decode($diveCentersObject->gallery);
@endphp

@if(!empty($gallery_images))
<hr class="type hrblue">
<section class="dive_gallery_sections padding-bottom-20">
	<div class = "ui container">
		<h2 class ="text-center">DIVE CENTER IMAGES</h2>
		@if(count($gallery_images) > 3)
			<div class="slider" id="center-gallery-img">
				@foreach($gallery_images  as $gallery)
					<div class = "image"><img src = "{{asset('assets/images/scubaya/dive_center/gallery/'.$diveCentersObject->merchant_key.'/diveCenter-'.$diveCentersObject->id.'/'.$gallery)}}" width = "100%" height="250px" /></div>
				@endforeach
			</div>
		@else
			<div class="ui medium images text-center">
				@foreach($gallery_images  as $gallery)
					<img src = "{{asset('assets/images/scubaya/dive_center/gallery/'.$diveCentersObject->merchant_key.'/diveCenter-'.$diveCentersObject->id.'/'.$gallery)}}"/>
				@endforeach
			</div>
		@endif
		<div class ="text-center" style = "margin-top:10px;">
			<button class="ui primary button">Show all images</button>
		</div>
	</div>	
</section>
@endif
