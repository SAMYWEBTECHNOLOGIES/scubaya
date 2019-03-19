@extends('front.layouts.master')
@section('page-title','Home')
@section('content')
    @php
        use Jenssegers\Agent\Agent;
        $agent = new Agent();
        $cookie_present   =   Request::hasCookie('scubaya_dive_in');

        if($cookie_present){
            $user_id     =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);
            $user        =   \App\Scubaya\model\User::where('id',$user_id)->first();
        }

        $cart_items = 0;
        $course_count       =   !$cookie_present?(Request::hasCookie('courses')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['courses']))) :0 ): \App\Scubaya\model\DiveCenterCheckout::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING]])->count();
        $product_count      =   !$cookie_present?(Request::hasCookie('products')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['products']))) :0 ): \App\Scubaya\model\ProductCheckouts::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING]])->count();
        $hotel_count        =   !$cookie_present?(Request::hasCookie('hotel')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['hotel']))) :0 ): \App\Scubaya\model\HotelCheckout::where([['user_key',$user_id],['status',CHECKOUT_PENDING]])->count();

        $cart_items         =   $course_count + $product_count + $hotel_count;
    @endphp

    @include('front._partials.header')
    <script type="text/javascript">
        if($('.sticky.sub-header').css('display')){
            $('.sticky.sub-header').css('display','none');
        }
    </script>
    <div class="video-container">
        <video id="bgvid" playsinline autoplay muted loop>
            <source src="{{ asset('assets/video/scuba_diving.webm') }}" type="video/webm">
            <source src="{{ asset('assets/video/scuba_diving.mp4') }}" type="video/mp4">
        </video>
        <div class="scu-main-overlay"></div>
        <div class="scu-main">
            <div class="ui container">
                <div class="ui stackable grid">
                    <div class="sixteen wide right aligned column">
                        <div class="scu-top-menu">
                            @if(!$cookie_present)
                                <a href="{{route('scubaya::checkout::cart')}}">
                                    <i class="icon shopping cart">
                                    </i>
                                </a>
                                <a href="#" class="scu-signin-btn">Sign In</a>
                                <a href="#" class="scu-signup-btn">Sign Up</a>
                            @else
                                <a target="_blank" href="{{route('scubaya::user::dashboard')}}"><i class="user icon"></i>{{\Illuminate\Support\Facades\Crypt::decrypt($user->first_name)}}</a>
                            @endif

                        </div>
                    </div>
                    <div class="two wide column"></div>

                    <div class="twelve wide center aligned column">
                        <div class="scu-main-content">
                            <img src="{{ asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}" width="300"/>

                            <h1 style="color:#fff">Scubaya, We Share The <span>Ocean</span></h1>
                            <div class="scu-filter-form">
                                <div class="ui form text-center radio_wrap">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" value="{{DIVE_CENTER}}" name="scubaya-filter" {{--checked="checked"--}}>
                                                <label>Dive Centers</label>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" value="{{HOTEL}}" name="scubaya-filter" >
                                                <label>Hotels</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="sixteen wide column keyword_wrap">
                                        <div class="_puzkdo">
                                            <div>
                                                <div class="_1u2299ij">
                                                    <div>
                                                        <div class="_b4huy9n">
                                                            <div class="_gor68n">
                                                                <div>
                                                                    <div class="_e296pg">
                                                                        <div class="_9hxttoo"><label class="_1m8bb6v">Where</label>
                                                                            <div dir="ltr">
                                                                                <div class="_v0d63vq">
                                                                                    <div class="_ncmdki">
                                                                                        <div class="_bp0th8">
                                                                                            <div class="_1miobth loading icon">
                                                                                                <i class="search icon"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="_178faes ui icon input search loading">
                                                                                        <input type="text" class="_k3jto05 scubaya-search " autocomplete="off" name="query" placeholder="Search Destination...">
                                                                                        <div class="results"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="_17u98ky">
                                                            <a class="_2930ex" href="#" target="_blank" id="search-form">
                                                                <button type="submit" class="_vuq6rr">
                                                                    <span>Search</span>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="scu-explore-more">
                <button class="ui inverted button">Explore<br><i class="big angle single down icon"></i></button>
            </div>
        </div>
    </div>

    <div id="context">
        @if(count($destinations) || count($dive_centers) || count($hotels))
        <section class="scu-popular">
            @if(count($destinations))
            <div class="ui container scu-popular-destination">
                <h2 class="text-center blue">POPULAR DESTINATIONS</h2>
                <div class="popular_destinations" style="display: none">
                    @foreach($destinations as $destination)
                        <a href="{{ route('scubaya::destination::destination_details', [ $destination->id, $destination->name ]) }}">
                            <div class="ui card">
                                <div class="image">
                                    <img alt="{{$destination->image}}" src="{{ asset('assets/images/scubaya/destination/'.$destination->id.'-'.$destination->image) }}">
                                </div>

                                <div class="content">
                                    <div class="header">
                                        {{ ucwords($destination->name) }}
                                    </div>
                                    <div class="meta">
                                        <p>{{ ucwords($destination->country) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($dive_centers))
            <div class="ui container scu-popular-dive">
                <h2 class="text-center blue">POPULAR DIVE CENTER</h2>
                <div class="popular_dive_center" style="display: none">
                    @foreach($dive_centers as $diveCenter)
                        <a href="{{ route('scubaya::dive_center_details', [$diveCenter->id, str_slug($diveCenter->name)]) }}">
                            <div class="ui card">
                                <div class="image">
                                    <img alt="{{$diveCenter->image}}" src="{{ asset('/assets/images/scubaya/dive_center/'.$diveCenter->merchant_key.'/'.$diveCenter->id.'-'.$diveCenter->image) }}">
                                </div>

                                <div class="content">
                                    <div class="header">
                                        {{ ucwords($diveCenter->name) }}
                                    </div>
                                    <div class="meta">
                                        <p>{{ ucwords($diveCenter->country) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($hotels))
            <div class="ui container scu-popular-hotels">
                <h2 class="text-center blue">POPULAR HOTELS</h2>
                <div class="popular_hotels" style="display: none">
                    @foreach($hotels as $hotel)
                        <a href="{{ route('scubaya::hotel::hotel_details', [$hotel->id, str_slug($hotel->name)]) }}">
                            <div class="ui card">
                                <div class="image">
                                    <img alt="{{$hotel->image}}" src="{{ asset('/assets/images/scubaya/hotel/'.$hotel->merchant_primary_id.'/'.$hotel->id.'-'.$hotel->image) }}">
                                </div>

                                <div class="content">
                                    <div class="header">
                                        {{ ucwords($hotel->name) }}
                                    </div>
                                    <div class="meta">
                                        <p>{{ ucwords($hotel->country) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </section>
        @endif

        <section class="scu-subscription-section">
            <div class="subscription-overlay">
                <div class="ui container scu-subscription">
                    <div class="ui center aligned grid">
                        <div class="column">
                            @if(isset($homepageContent->subscription_content))
                               @php echo $homepageContent->subscription_content @endphp
                            @else
                                <h2>SAVE TIME AND GET BEST DEALS</h2>
                                <h3>We will send you only the best deals and latest news</h3>
                            @endif
                            <form id="subscribe" name="subscription-form">
                                <span class="text-center" id="success-message"></span>
                                <span class="text-center" id="email_warning"></span>
                                <div class="ui form">
                                    <div class="inline field">
                                        <input type="email" id="email" name="email" placeholder="joe@schmoe.com">
                                        <button data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Subscribing" name="submit" id="submit" class="ui submit button">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="scu-services-section">
            <div class="ui container scu-services">
                <div class="ui three column stackable center aligned grid">
                    @if(isset($homepageContent->features_content) && json_decode($homepageContent->features_content) != null)
                        @foreach(json_decode($homepageContent->features_content) as $key => $content)
                            <div class="column">
                                @php echo $content @endphp
                            </div>
                        @endforeach
                    @else
                        <div class="column">
                            <i class="map icon"></i>
                            <h3 class="text-center">Search Your Destination</h3>
                        </div>

                        <div class="column">
                            <i class="location arrow icon"></i>
                            <h3 class="text-center">Book Your Travel</h3>
                        </div>

                        <div class="column">
                            <i class="world icon"></i>
                            <h3 class="text-center">Support The Oceans</h3>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <section class="scu-about-section">
            <div class="about-overlay">
                <div class="ui container scu-about">
                    <div class="ui center aligned grid">
                        <div class="column">
                            @if(isset($homepageContent->blog_content))
                               @php echo $homepageContent->blog_content @endphp
                            @else
                                <p>You can signup or follow us about the latest news about us and our progress on our Scubaya Blog</p>
                                <a href="https://blog.scubaya.com" target="_blank"><button class="ui inverted button">Blog</button></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="scu-map-section">
            <div id="map"></div>
        </section>
    </div>

    @include('front.layouts.subscription_script')
@endsection
@section('script-extra')
        <script type="text/javascript">
        var count = parseInt('{{$cart_items}}');
        if (!count) {
            $('.cart-count').hide();
        }

        var isMobileagent   =   ('{{var_export($agent->isMobile())}}'=='true');
        var window_height = isMobileagent?$(window).height():screen.height-100;
        $('.scu-main').css('height',window_height);
        var scu_main_height = $(".scu-main").innerHeight();
        $('.video-container').css('min-height',window_height);
        $(".scu-main-content").css("margin-top", scu_main_height / 2 - 250 + "px");

        $(document).ready(function () {
            $('.sticky.sub-header').css('display','none');
            $(window).scroll(function(){
                if ($(this).scrollTop() > 25) {
                    $('.sticky.sub-header').css('display','block');
                    $('.sub-header').css('visibility', 'visible').css('transition', 'opacity 600ms, visibility 600ms');
                } else {
                    if(!isMobileagent){
                        $('.sticky.sub-header').css('display','none');
                    }
                }
            });

            $('.popular_dive_center').slick({
                infinite: false,
                autoplay: false,
                autoplaySpeed:800,
                pauseOnHover:true,
                centerPadding: '12%',
                slidesToShow: 4,
                speed: 500,
                arrows:true,
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
            $('.popular_dive_center').show();

            $('.popular_hotels').slick({
                infinite: false,
                autoplay: false,
                autoplaySpeed:800,
                pauseOnHover:true,
                centerPadding: '12%',
                slidesToShow: 4,
                speed: 500,
                arrows:true,
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
            $('.popular_hotels').show();

            $('.popular_destinations').slick({
                infinite: false,
                autoplay: false,
                autoplaySpeed:800,
                pauseOnHover:true,
                centerPadding: '12%',
                slidesToShow: 4,
                speed: 500,
                arrows:true,
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
            $('.popular_destinations').show();

            $(".video-container .scu-main .scu-explore-more").click(function () {
                $('html, body').animate({
                    scrollTop: $(".scu-popular").offset().top
                }, 2000);
            });

            $('.sub-header').sticky({
                context: '#context'
            });

            initMap();
        });

        function toTitleCase(str){
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        }

       function initMap() {
           var hotels   = JSON.parse('{!! json_encode($merchantSubAccountInfo['hotels']) !!}');
           var centers  = JSON.parse('{!! json_encode($merchantSubAccountInfo['centers']) !!}');

           var map;

           if(hotels.length > 0 && hotels[0][4] && hotels[0][5]) { console.log(hotels[0][4]);
               map    = L.map( 'map', {center: [hotels[0][4], hotels[0][5]],zoom:4, scrollWheelZoom: false});
           } else if(centers.length > 0 && centers[0][4] && centers[0][5]) {
               map    = L.map( 'map', {center: [centers[0][4], centers[0][5]],zoom:4, scrollWheelZoom: false});
           } else {
               map    = L.map( 'map', {center: [52.370216, 4.895168],zoom:4, scrollWheelZoom: false});
           }

           map.on('click', function() {
               if (map.scrollWheelZoom.enabled()) {
                   map.scrollWheelZoom.disable();
               }
               else {
                   map.scrollWheelZoom.enable();
               }
           });

           L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
               attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
           }).addTo( map );

           var hotelIcon = L.icon({
               iconUrl: '{{ asset('assets/images/web-application-icons/blue-marker.png') }}',
               iconSize: [40, 40], // size of the icon
               popupAnchor: [0,-15]
           });

           var centerIcon = L.icon({
               iconUrl: '{{ asset('assets/images/web-application-icons/green-marker.png') }}',
               iconSize: [40, 40], // size of the icon
               popupAnchor: [0,-15]
           });

           for (var i = 0; i < hotels.length; i++) {
               if(hotels[i][4] && hotels[i][5]) {
                   var marker   =   L.marker([hotels[i][4], hotels[i][5]], {icon: hotelIcon}).addTo(map);

                   var url      =   '{{route('scubaya::hotel::hotel_details',['--ID--', '--NAME--'])}}';
                   url          =   url.replace('--ID--', hotels[i][0]);

                   var hotelName    =   hotels[i][1];
                   hotelName        =   hotelName.replace(/\s+/g, '-').toLowerCase();

                   url              =   url.replace('--NAME--', hotelName);

                   marker.bindPopup(
                       '<div class="info-content">' +
                       '<img width="224" src="data:image/'+hotels[i][2]+';base64,'+hotels[i][3]+'" />'+
                       '<div class="content">'+
                       '<a href="'+url+'"><h4>'+hotels[i][1]+'</h4></a>' +
                       '<p>'+hotels[i][6]+'</p>' +
                       '</div>'+
                       '</div>'
                   );
               }
           }

           for (var i = 0; i < centers.length; i++) {
               if(centers[i][4] && centers[i][5]) {
                   var marker   =   L.marker([centers[i][4], centers[i][5]], {icon: centerIcon}).addTo(map);

                   var url      =   '{{route('scubaya::dive_center_details',['--ID--', '--NAME--'])}}';
                   url          =   url.replace('--ID--', centers[i][0]);

                   var centerName   =   centers[i][1];
                   centerName       =   centerName.replace(/\s+/g, '-').toLowerCase();

                   url              =   url.replace('--NAME--', centerName);

                   marker.bindPopup(
                       '<div class="info-content">' +
                       '<img width="224" src="data:image/'+centers[i][2]+';base64,'+centers[i][3]+'" />'+
                       '<div class="content">'+
                       '<a href="'+url+'"><h4>'+centers[i][1]+'</h4></a>' +
                       '<p>'+centers[i][6]+'</p>' +
                       '</div>'+
                       '</div>'
                   );
               }
           }

       }

        /*initialize the global variables*/
        var dive_center_url, hotel_url, destination_url, marine_life, scubaya_filter;

        scubaya_filter      =   $('input[name=scubaya-filter]:checked').val();
        marine_life         =   null;

        /*preparing the url to redirect the results when clicked*/
        dive_center_url     =   '{{route('scubaya::diveCenters',['search'])}}'+ '?' ;
        hotel_url           =   '{{route('scubaya::hotel::hotels',['search'])}}'+ '?';
        destination_url     =   '{{route('scubaya::destination::destinations',['search'])}}'+ '?';

        /*ajax query when user hit the search bar*/
        $('.scubaya-search').keyup(function () {
            $('#search-form').attr('action','#');
            var search_query = this.value;

            var url = "{{route('scubaya::search_all')}}";

            if(search_query.length){
                /*start the loader*/
                if(!$(this).closest('div').find('.search').length){
                    $(this).after('<i class="search icon"></i>');
                }
                /*define this to use inside the ajax function*/
                var t   =   this;

                /*ajax for search*/
                console.log(search_query);
                $.get(url, {query: search_query,filter:scubaya_filter},
                    function (data) {
                        var results         =   JSON.parse(data.results);
                        $('.results').addClass('transition visible').css("cssText",'display: block !important;').empty();

                        if(!$.isEmptyObject(results)){
                            if(scubaya_filter   ==  '{{DIVE_CENTER}}'){

                                $.each(results,function(index, divecenter){

                                    var dive_center_search_url     =   dive_center_url + 'country='+ divecenter.country + '&name='+divecenter.name;

                                    $('.results').append(
                                        '<a class="result" target="_blank" href="'+dive_center_search_url+'">' +
                                        '   <div class="content">' +
                                        '       <div class="title"><i class="home icon"></i>'+divecenter.name+ '('+divecenter.country+') </div>' +
                                        '           <div class="description">' +
                                        '               <span>'+divecenter.address+'</span>' +
                                        '       </div>' +
                                        '   </div>' +
                                        '</a>');

                                    if(index == (results.length - 1)){
                                        $('#search-form').attr('href', dive_center_url + 'query='+ search_query );
                                    }
                                });

                            } else if(scubaya_filter   ==  '{{HOTEL}}') {

                                $.each(results,function(index, hotel){
                                    var hotel_search_url     =   hotel_url + 'country='+ hotel.country + '&name='+hotel.name;
                                    $('.results').append(
                                        '<a class="result" target="_blank" href="'+hotel_search_url+'">\n' +
                                        '    <div class="content">\n' +
                                        '        <div class="title"><i class="home icon"></i>' + hotel.name + '(' + hotel.country + ')' +
                                        '           <div class="description">\n' +
                                        '               <span>'+hotel.address+'</span>\n' +
                                        '           </div>' +
                                        '        </div>\n' +
                                        '    </div>\n' +
                                        '</a>');

                                    if(index == (results.length - 1)){
                                        $('#search-form').attr('href', hotel_url + 'country=' + hotel.country);
                                    }
                                });

                            } else {

                                $.each(results, function(index, destination){
                                    var destination_search_url     =   destination_url + 'country='+ destination.country + '&name='+destination.name;
                                    $('.results').append(
                                        '<a class="result" target="_blank" href="'+destination_search_url+'">\n' +
                                        '    <div class="content">\n' +
                                        '        <div class="title"><i class="home icon"></i>' + destination.name + '(' + destination.country + ')' +
                                        '           <div class="description">\n' +
                                        '               <span>'+destination.location+'</span>\n' +
                                        '           </div>' +
                                        '        </div>\n' +
                                        '    </div>\n' +
                                        '</a>');

                                    if(index == (results.length - 1)){
                                        $('#search-form').attr('href', destination_url + 'country=' + destination.country);
                                    }
                                });

                            }
                        } else {
                            /*append when no results found*/
                            $('.results').append(
                                '<a class="result" >\n' +
                                '    <div class="content">\n' +
                                '        <div class="title">No results found' +
                                '        </div>\n' +
                                '    </div>\n' +
                                '</a>');
                            $('#search-form').attr('href','#');
                        }

                        $(t).closest('div').find(".search").remove();
                    });
            }
        });

        /*remove the search popup when it removing the words*/
        $('.scubaya-search').keydown(function () {
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 ){
                $('.scubaya-search').closest('div').find(".search").remove();
                $('.results').removeClass('transition').removeClass('visible').css('cssText','').empty();
                $('#search-form').attr('href','#');
            }
        });

        /*remove search popup when click outside */
        $(document).mouseup(function(e){
            if(!$('.results').is(e.target) && $('.results').has(e.target).length === 0){
                $('.results').removeClass('transition').removeClass('visible').css('cssText','');
                $('.scubaya-search').closest('div').find(".search").remove();
            }
        });

        $('.ui.radio.checkbox').click(function () {
            $(this).find('input').prop('checked',true);
            scubaya_filter  =   $('input[name=scubaya-filter]:checked').val();
        });
    </script>
@endsection
