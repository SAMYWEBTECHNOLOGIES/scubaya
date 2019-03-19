@if($courses && count($courses))
<hr class="type hrblue">
<section class="dive-center-product-section">
    <h2 class = "blue">Products</h2>
	<div class = "product_box courses">
        <h3 class="text-center product-header">COURSES</h3>
         <div class="slider products">
            @foreach($courses as $course)
                <div class="column course-card">
                    <div class="ui blue special cards">
                        <div class="card">
                            <div class="blurring dimmable image">
                                <div class="ui dimmer">
                                    <div class="content">
                                        <div class="center">
                                            <a href="{{ route('scubaya::course_details', [$diveCentersObject->id, str_slug($diveCentersObject->name), str_slug($course->course_name), $course->id]) }}">
                                                <div class="ui inverted button">View Details</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @if($course->image)
                                    <img src="{{ asset('assets/images/scubaya/shop/courses/'.$course->merchant_key.'/'.$course->id.'-'.$course->image) }}" alt="Scubaya Dive Center Course Image" />
                                @else
                                    <img src="{{ asset('assets/images/default.png') }}" alt="Scubaya Dive Center Course Image" />
                                @endif
                            </div>
                            <div class="content">
                                <div class="header">{{ ucwords($course->course_name) }}</div>
                                <?php
                                $pricing    =   (array)json_decode($course->course_pricing);
                                $totalPrice =   ($exchangeRate[$course->merchant_key]['rate']) * $pricing['price'];
                                ?>
                                <div class="meta">
                                    <span class="date">{{ date(" j M y", strtotime( str_replace('-', '/', $course->course_start_date) ) ).' - '.date(  "j M y", strtotime( str_replace('-', '/', $course->course_end_date) ) ) }}</span>
                                    <p class="course-price"><strong>{{$exchangeRate[$course->merchant_key]['symbol'].' '.$totalPrice }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
	</div>
</section>
@endif