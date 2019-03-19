{{--only show when number of hotels are greator than 4,since grid has been taken is of 4--}}
@if(count($hotel_recommendations) >= 4)
    <hr class="type hrblue">
    <hr class="type hrblue">
    <section class="recommended-accommodations">
        <div class="ui container">
            <div class="ui stackable four column grid">
                <h2 class="ui sixteen wide center aligned column">Recommended Accommodations By {{$diveCentersObject->name}}</h2>
                @foreach($hotel_recommendations as $hotels)
                    <div class="column hotel_image">
                        <div class="ui special cards">
                            <div class="card" style="min-height: 340px;">
                                <div class="blurring dimmable image">
                                    <div class="ui dimmer">
                                        <div class="content">
                                            <div class="center">
                                                <a href="#">
                                                    <div class="ui inverted button">VIEW</div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="{{asset('assets/images/scubaya/hotel/'.$diveCentersObject->merchant_key.'/'.$hotels->id.'-'.$hotels->image)}}"
                                         class="tiny image">
                                </div>
                                <div class="content">
                                    <a class="header">{{$hotels->name}}</a>
                                    <div class="meta">
                                        <span>{{$hotels->city.', '.$hotels->state}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="sixteen wide center aligned column">
                    <a href="{{route('scubaya::hotel::hotels')}}">
                        <button type="submit" class="ui primary button">Show More</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif