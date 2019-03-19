@extends('front.layouts.master')
@section('page-title')
    Dive-centers
@endsection
@section('content')
    @include('front._partials.header')

    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
    @endphp

    <section class="dc-image-slider">
        <div class="ui fluid container">
            <ul class="rslides">
                <li><img alt="{{$diveCentersObject->name}}" src="{{ asset('assets/images/scubaya/dive_center/'.$diveCentersObject->merchant_key.'/'.$diveCentersObject->id.'-'.$diveCentersObject->image) }}"/></li>
            </ul>
        </div>
    </section>

    <div id="dc-detail-context">
        <div class ="ui raised segment divesite_margin" style="width:90%;margin:auto;" >
            <section class="dive-center-detail-section">
                <div class="dive-center-detail" >
                    <div class="ui stackable grid">
                        <div class = "sixten wide column">
                            <div class="ui breadcrumb">
                                <span class="fa fa-home blue"></span>
                                <div class="section">
                                    <a href="{{ route('scubaya::diveCenters') }}" class="blue">Dive Centers</a>
                                </div>
                                <i class="blue fa fa-angle-right"></i>
                                <div class="blue section">{{ ucwords($diveCentersObject->name) }}</div>
                            </div>
                        </div>
                        <div class="eleven wide column">
                            <div class="ui grid">
                                @if($Agent->isMobile())
                                    <div class="sixteen wide column">
                                    <span class= "blue divecenter_name">{{ ucwords($diveCentersObject->name) }}</span><br>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked" ></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span><br>
                                    <label class="blue">Ranked 2 out of 15
                                    in {{$diveCentersObject->country}}</label>
                                    <p style = "margin-top:2px;">
                                        <i class="fa fa-map-marker"></i>
                                        {{$diveCentersObject->address}}
                                    </p>
                                </div>
                                 @else
                                <div class="sixteen wide column">
                                    <span class= "blue divecenter_name">{{ ucwords($diveCentersObject->name) }}</span>
                                    <div class="dive_center_rank">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked" ></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </div>
                                    <p class="blue">Ranked 2 out of 15
                                    in {{$diveCentersObject->country}}</p>
                                    <p class="meta">
                                        <i class="fa fa-map-marker"></i>
                                        {{$diveCentersObject->address}}
                                    </p>
                                </div>
                                @endif
                                <div class="sixteen wide column  description_less">
                                    {!! $diveCentersObject->long_description !!}
                                </div>
                            </div>
                        </div>
                        <div class="five wide column">
                            @if($showContactModule)
                                @include('front.home.dive_center.section.divecenter_contact_module_section')
                            @else
                                @include('front.home.dive_center.section.divecenter_availability_section')
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @include('front.home.dive_center.section.divecenter_info_section')
            @include('front.home.dive_center.section.divecenter_gear_rental_section')
            @include('front.home.dive_center.section.divecenter_readbefore_section')
            @include('front.home.dive_center.section.divecenter_products_section')
            @include('front.home.dive_center.section.divecenter_sites_section')
        </div>
        @include('front.home.dive_center.section.divecenter_gallery_section')
        @include('front.home.dive_center.section.divecenter_hotel_section')
    </div>
@endsection

@php
    $clientGeoInfo =   geoip($_SERVER['REMOTE_ADDR']);
@endphp

@section('script-extra')
    <script type="text/javascript">
        var diveSitesWithinRadius  =   JSON.parse('{!! json_encode($diveSites['diveSitesWithinRadius']) !!}');
        var diveSitesNotInRadius   =   JSON.parse('{!! json_encode($diveSites['diveSitesNotInRadius']) !!}');

        var diveCenterLatLong      =   {
            'lat'   :  '{{ $diveCentersObject->latitude }}',
            'lng'   :  '{{ $diveCentersObject->longitude }}'
        };

        var clientLatLong          =   {
            'lat'   :  '{{ $clientGeoInfo['lat'] }}',
            'lng'   :  '{{ $clientGeoInfo['lon'] }}'
        };

        /* read less and more for description */
        jQuery.fn.shorten = function(settings) {
            var config = {
                showChars : 400,
                ellipsesText : " ",
                moreText : "Read More <i class='fa fa-chevron-down' style='font-size:15px'></i>",
                lessText : "Read Less <i class='fa fa-chevron-up' style='font-size:15px'></i>"
            };

            if (settings) {
                jQuery.extend(config, settings);
            }

            jQuery('body').on('click','.morelink', function() {
                var his = jQuery(this);
                if (his.hasClass('less')) {
                    his.removeClass('less');
                    his.html(config.moreText);
                    // jQuery('#description').css('height', '11.6em');
                } else {
                    his.addClass('less');
                    his.html(config.lessText);
                    // jQuery('#description').css('height', jQuery('#description').height()+'px');
                    /*console.log(jQuery('#description').height());*/
                }
                his.parent().prev().toggle();
                his.prev().toggle();

                return false;
            });

            return this.each(function() {
                var his = jQuery(this);

                var content = his.html();
                if (content.length > config.showChars) {
                    var c = content.substr(0, config.showChars);
                    var h = content.substr(config.showChars , content.length - config.showChars);
                    var html = c + '<span class="moreellipses">' + config.ellipsesText + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript://nop/" class="morelink">' + config.moreText + '</a></span>';
                    his.html(html);
                    jQuery(".morecontent span").hide();
                }
            });
        };

        jQuery('.description_less p').shorten();

        $(document).ready(function () {

            $('.sub-header').sticky({
                context: '#dc-detail-context'
            });

            $('.special.cards .image').dimmer({
                on: 'hover'
            });

            $('.menu .item').tab();

            $('#rangestart').calendar({
                type: 'date',
                endCalendar: $('#rangeend')
            });

            $('#rangeend').calendar({
                type: 'date',
                startCalendar: $('#rangestart')
            });

            $('.cancellation-policy').click(function(){
                $('.cancellation-policy-modal').modal('show');
            });

            productSlider();
            gallery();
            initDiveCenterMap();
            
            if(diveSitesNotInRadius.length > 0 || diveSitesWithinRadius.length > 0) {
                diveSites();
            }

            $('#check-product-availability').click(function(e){
                e.preventDefault();
                var checkIn     =   $('#check_in').val();
                var checkOut    =   $('#check_out').val();
                var dcId        =   '{{ $diveCentersObject->id }}';
                var token       =   '{{ csrf_token() }}';

                if(checkIn && checkOut) {
                    $('#check-in-error').text('');
                    $('#check-out-error').text('');

                    $.ajax({
                        url:'{{ route('scubaya::product_availability') }}',
                        method:'post',
                        data:{
                            dcId:dcId,
                            check_in:checkIn,
                            check_out:checkOut,
                            _token:token
                        }
                    }).done(function (data) {

                        $('html,body').animate({
                            scrollTop: $(".dive-center-product-section").offset().top
                        }, 600);

                        $('.product_box.courses').remove();

                        var html        =   '';
                        var courseUrl   =   '{{ route('scubaya::course_details',['--dcId--', '--dcName--', '--cname--', '--cid--']) }}';

                        $.each(data.courses, function (key, value) {
                            $.each(value, function(k, v){
                                var dcName      =   '{{ $diveCentersObject->name }}';
                                var symbol      =   '{{ $exchangeRate[$diveCentersObject->merchant_key]['symbol'] }}';
                                var rate        =   '{{ $exchangeRate[$diveCentersObject->merchant_key]['rate'] }}';

                                courseUrl       =   courseUrl.replace('--dcId--', dcId);
                                courseUrl       =   courseUrl.replace('--dcName--', convertToSlug(dcName));
                                courseUrl       =   courseUrl.replace('--cname--', convertToSlug(v.cname));
                                courseUrl       =   courseUrl.replace('--cid--', v.cid);

                                html    +=  '<div class="column course-card">\n' +
                                    '    <div class="ui blue special cards">\n' +
                                    '        <div class="card">\n' +
                                    '            <div class="blurring dimmable image">\n' +
                                    '                <div class="ui dimmer">\n' +
                                    '                    <div class="content">\n' +
                                    '                        <div class="center">\n' +
                                    '                            <a href="'+courseUrl+'">\n'+
                                    '                                <div class="ui inverted button">View Details</div>\n' +
                                    '                            </a>\n' +
                                    '                        </div>\n' +
                                    '                    </div>\n' +
                                    '                </div>\n' +
                                    '                <img src="'+v.image+'" alt="Scubaya Dive Center Course Image" />\n' +
                                    '            </div>\n' +
                                    '            <div class="content">\n' +
                                    '                <div class="header">'+v.cname+'</div>\n' +
                                    '                <div class="meta">\n'+
                                    '                    <span class="date">'+v.start_date+' - '+v.end_date+'</span>\n'+
                                    '                    <p class="course-price"><strong>'+symbol+v.price * rate+'</strong></p>\n'+
                                    '                </div>\n'+
                                    '            </div>\n'+
                                    '        </div>\n'+
                                    '    </div>\n'+
                                    '</div>';
                            });
                        });

                        $('.dive-center-product-section').html(
                                '<h2 class = "blue">Products</h2>'+
                                '<div class = "product_box courses">\n' +
                                '     <h3 class="text-center product-header">COURSES</h3>\n' +
                                '     <div class="slider products">'+html+'</div>'+
                                '</div>'
                        );

                        productSlider();

                        $('.special.cards .image').dimmer({
                            on: 'hover'
                        });
                    });
                } else {
                    if(! checkIn){
                        $('#check-in-error').text('Select check in date.').css({
                            'color':'red',
                            'padding-top':'8px'
                        });
                    } else {
                        $('#check-in-error').text('');
                    }

                    if(! checkOut){
                        $('#check-out-error').text('Select check out date.').css({
                            'color':'red',
                            'padding-top':'8px'
                        });
                    } else {
                        $('#check-out-error').text('');
                    }
                }
            });

            $('#send-message').click(function(e){
                e.preventDefault();
                var email   =   $('#email').val();
                var query   =   $('#message').val();
                var key     =   '{{ (new \Hashids\Hashids())->encode($diveCentersObject->merchant_key) }}';

                if(isEmail(email)) {
                    $('#error-email').text(' ');

                    var token  =   '{{ csrf_token() }}';

                    //$('#contact-form #send-message').addClass('loading');
                    $('#contact-form #send-message').html($('#contact-form #send-message').data('loading-text'));

                    $.ajax({
                        url:'{{ route('scubaya::user_query') }}',
                        method:'post',
                        data:{ key:key, email:email, query:query, _token:token},
                        success: function(response){
                            $('#contact-form h3').after(
                                '<div class="ui success message sixteen wide column ">\n' +
                                '  <p>'+response.message.success+'</p>\n' +
                                '</div>');

                            $('#email').val('');
                            $('#message').val('');
                            $('#contact-form .success.message').transition({
                                animation  : 'fade out',
                                duration   : '3s',
                                onComplete : function() {
                                    $('#contact-form .success.message').remove();
                                }
                            });
                            $('#contact-form #send-message').html('Send Now');
                        },
                        error: function(response) { console.log(response);
                            $('#contact-form h3').after(
                                '<div class="ui error message sixteen wide column ">\n' +
                                '  <p>'+response.message.error+'</p>\n' +
                                '</div>');

                            $('#contact-form .error.message').transition({
                                animation  : 'fade out',
                                duration   : '3s',
                                onComplete : function() {
                                    $('#contact-form .error.message').remove();
                                }
                            });
                        }
                    });
                } else {
                    $('#error-email').text('Please enter valid email address.')
                }
            });
        });

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

        function productSlider() {
            $('.products').slick({
                dots: true,
                speed: 300,
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 4,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: false,
                            dots: true,
                            arrows: false,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            arrows: false,
                            dots:true
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false,
                            dots:true,
                        }
                    }
                ]
            });
        }

        function gallery() {
            $('#center-gallery-img').slick({
                dots: false,
                arrows: false,
                infinite: true,
                centerMode: true,
                slidesToShow: 3,
                slidesToScroll:3,

                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }

        function initDiveCenterMap() {

            var markers = {
                "lat": diveCenterLatLong.lat ? diveCenterLatLong.lat : clientLatLong.lat,
                "lng": diveCenterLatLong.lng ? diveCenterLatLong.lng : clientLatLong.lng
            };

            var map    = L.map( 'dive_center_map', {
                    center: [markers.lat, markers.lng],
                    zoom:4,
                    scrollWheelZoom: false
            });

            L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.scubaya.com">Scubaya.com</a>'
            }).addTo( map );

            if(diveCenterLatLong.lat && diveCenterLatLong.lng) {
                L.marker([diveCenterLatLong.lat, diveCenterLatLong.lng])
                    .addTo(map)
                    .bindPopup('<p>{{ $diveCentersObject->address  }}</p>');
            }
        }

        function diveSites() {
            var markers = {
                "lat": diveCenterLatLong.lat ? diveCenterLatLong.lat : clientLatLong.lat,
                "lng": diveCenterLatLong.lng ? diveCenterLatLong.lng : clientLatLong.lng
            };

            var map    = L.map( 'dive-sites', {
                center: [markers.lat, markers.lng],
                zoom:4,
                scrollWheelZoom: false
            });

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

            $.each(diveSitesWithinRadius, function(k, v){
                var markers  =    L.marker([v.lat, v.long]);
                markers.addTo(map);

                var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                if(v.image) {
                    src     =   src.replace('--IMAGE--', v.key+'-'+v.image);
                } else {
                    src     =   '{{ asset('images/--IMAGE--') }}';
                    src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                }

                markers.bindPopup(
                    '<div class="info-content">' +
                    '<img width="224" src="'+src+'">'+
                    '<div class="content">'+
                    '<a onclick="diveSite('+v.key+')">'+toTitleCase(v.name)+'</a>' +
                    '</div>'+
                    '</div>'
                );
            });

            map.on('zoomend', function(){
                if (map.getZoom() > 7) {
                    $.each(diveSitesNotInRadius, function(k, v){
                        var markers  =    L.marker([v.lat, v.long]);
                        markers.addTo(map);

                        var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                        if(v.image) {
                            src     =   src.replace('--IMAGE--', v.key+'-'+v.image);
                        } else {
                            src     =   '{{ asset('images/--IMAGE--') }}';
                            src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                        }

                        markers.bindPopup(
                            '<div class="info-content">' +
                            '<img width="224" src="'+src+'">'+
                            '<div class="content">'+
                            '<a onclick="diveSite('+v.key+')">'+toTitleCase(v.name)+'</a>' +
                            '</div>'+
                            '</div>'
                        );
                    });
                }
            });
        }

        function toTitleCase(str){
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        }

        function convertToSlug(Text)
        {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-')
                ;
        }

        function diveSite(key) {
            var token  =   '{{ csrf_token() }}';

            $.ajax({
                url:  '{{ route('scubaya::diveSiteById') }}',
                method:'post',
                data: {key:key, _token:token},
                dataType:"JSON",
                success: function (response) {
                    var src =   '{{ asset('assets/images/scubaya/dive_sites/--IMAGE--') }}';

                    if(response.image) {
                        src     =   src.replace('--IMAGE--', response.id+'-'+response.image);
                    } else {
                        src     =   '{{ asset('images/--IMAGE--') }}';
                        src     =   src.replace('--IMAGE--', 'dive-site.jpg');
                    }

                    $('#dive-site-name').text(toTitleCase(response.name));
                    $('#dive-site-image').attr('src', src);
                    $('#dive-level').text(toTitleCase(response.diver_level));
                    $('#max-depth').text(response.max_depth+'m');
                    $('#avg-depth').text(response.avg_depth+'m');
                    $('#current').text(toTitleCase(response.current));
                    $('#max-visibility').text(response.max_visibility+'m');
                    $('#avg-visibility').text(response.avg_visibility+'m');
                },
                error : function (error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
