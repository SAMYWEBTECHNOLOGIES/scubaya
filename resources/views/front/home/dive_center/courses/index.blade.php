@extends('front.layouts.master')
@section('page-title')
    {{ $course->course_name }}
@endsection
@section('content')
    @include('front._partials.header')

    <div id="course-detail-context">
        <section class="scu-course-description">
            <div class="ui container">
                <h1>{{ ucwords($course->course_name) }}</h1>

                <?php
                $location       = json_decode($course->location);

                $courseDuration = json_decode($course->course_days);

                $coursePricing  = json_decode($course->course_pricing);
                ?>

                @if(!empty($location->address))
                <h5>
                    <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $location->address }}
                </h5>
                @endif

                {!! @$course->description !!}

                <div class="ui celled grid">
                    <div class="row">
                        <div class="four wide column">
                            <h4>Duration</h4>
                            <div class="meta">  {{ $courseDuration->no_of_days or '---' }} days</div>
                        </div>

                        <div class="four wide column">
                            <h4>Price Per Person</h4>
                            <div class="meta">  {{ $exchangeRate[$course->merchant_key]['symbol'].' '.($exchangeRate[$course->merchant_key]['rate'] * $coursePricing->price ) }} </div>
                        </div>

                        <div class="four wide column center aligned">
                            <i class="users icon"></i>
                            <select class="ui dropdown">
                                @for($i = $coursePricing->min_people; $i <= $coursePricing->max_people; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="four wide column center aligned">
                            <button class="ui inverted blue button add-to-trip">Add To Trip</button>
                        </div>
                    </div>
                </div>

                @if($course->cancellation_detail)
                    <div class="cancellation-details"><strong>CANCELLATION POLICY
                            : </strong>{!! $course->cancellation_detail !!}</div>
                @endif
            </div>
        </section>

        <?php
        $productsInfo = array();
        $products = (array)json_decode($course->products);
        ?>

        @if(count($products))
            <section class="scu-products">
                <div class="ui container">
                    <h1>Products</h1>
                    <div class="ui stackable four column grid">
                        @foreach($products as $key => $value)
                            <?php
                            $value = (array)($value);

                            if($value['required'] == 1) {
                            $productInfo = \App\Scubaya\model\Products::where('id', $key)->first();

                            $mimeType = explode('.', $productInfo->product_image);
                            $imageContent = base64_encode(@file_get_contents(asset('assets/images/scubaya/shop/products/' . $productInfo->merchant_key . '/' . $productInfo->id . '-' . $productInfo->product_image)));
                            $productsInfo[$productInfo->id]['image']            =   $imageContent;
                            $productsInfo[$productInfo->id]['mimeType']         =   strtoupper($mimeType[1]);
                            $productsInfo[$productInfo->id]['title']            =   strtoupper($productInfo->title);
                            $productsInfo[$productInfo->id]['weight']           =   $productInfo->weight;
                            $productsInfo[$productInfo->id]['tax']              =   $productInfo->tax;
                            $productsInfo[$productInfo->id]['manufacturer']     =   ucwords($productInfo->manufacturer);
                            $productsInfo[$productInfo->id]['color']            =   $productInfo->color;
                            $productsInfo[$productInfo->id]['available_from']   =   $productInfo->availability_from;
                            $productsInfo[$productInfo->id]['available_to']     =   $productInfo->availability_to;
                            $productsInfo[$productInfo->id]['price']            =   $exchangeRate[$course->merchant_key]['rate'] * $productInfo->price;
                            $productsInfo[$productInfo->id]['price_symbol']     =   $exchangeRate[$productInfo->merchant_key]['symbol'];
                            $productsInfo[$productInfo->id]['product_type']     =   $productInfo->product_type;
                            $productsInfo[$productInfo->id]['description']      =   $productInfo->description;
                            $productsInfo[$productInfo->id]['IE']               =   (int)$value['IE'];
                            ?>
                            <div class="column">
                                <div class="ui segment items"
                                     data-tooltip="This product is required and it's price is @if($value['IE']) included @else not included @endif in the course price."
                                     data-inverted="">
                                    <a class="ui right corner label show-product-info" id="{{ $productInfo->id }}">
                                        <i class="eye icon"></i>
                                    </a>
                                    <div class="item">
                                        <div class="image">
                                            <div class="ui @if($value['IE']) teal @else grey @endif ribbon label">
                                                @if($value['IE']) Included @else Excluded @endif
                                            </div>
                                            <img src="{{ asset('assets/images/scubaya/shop/products/'.$productInfo->merchant_key.'/'.$productInfo->id.'-'.$productInfo->product_image) }}">
                                        </div>
                                        <div class="middle aligned content">
                                            <h4>{{ ucwords($productInfo->title) }}</h4>
                                            <div class="meta">
                                                <span>{{ $exchangeRate[$productInfo->merchant_key]['symbol'].' '.($exchangeRate[$productInfo->merchant_key]['rate'] * $productInfo->price ) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        @endforeach
                    </div>

                    <div class="ui longer product modal transition scrolling">
                        <div class="header">
                            Quick Product Viewer
                        </div>
                        <div class="scrolling image content">
                            <div class="ui stackable grid">
                                <div class="four wide column">
                                    <div class="ui large image">
                                        <img id="product_image" src="">
                                    </div>
                                </div>
                                <div class="twelve wide column">
                                    <div class="ui stackable grid price-row">
                                        <div class="two column row">
                                            <div class="column">
                                                <h2 id="product_name"></h2>
                                                <p id="manufacturer_by" class="meta"></p>
                                            </div>
                                            <div class="column" id="product_type">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="product_description"></div>

                                    <div class="ui stackable grid price-row">
                                        <div class="three column row">
                                            <div class="column">
                                                <label>PRICE</label>
                                                <p id="product_price"><strong></strong></p>
                                            </div>

                                            <div class="column">
                                                <label>TAX</label>
                                                <p id="product_tax"><strong></strong></p>
                                            </div>

                                            <div class="column">
                                                <label>TOTAL</label>
                                                <p id="product_total"><strong></strong></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-other-info">
                                        <div class="product-weight-section">
                                            <label>WEIGHT :</label>
                                            <span id="product_weight"></span>
                                        </div>

                                        <div class="product-weight-section">
                                            <label>COLOR :</label>
                                            <span id="product_color"></span>
                                        </div>

                                        {{--<div class="product-weight-section">
                                            <label>AVAILABILITY :</label>
                                            <span id="product_availability"></span>
                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="ui primary approve button">
                                Add To Trip
                                <i class="right chevron icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if(json_decode($course->affiliates))
            <section class="scu-affiliations">
                <div class="ui center aligned container">
                    <h2>Affiliations</h2>
                    <?php $courseAffiliates = (array)json_decode($course->affiliates); ?>
                    @foreach($courseAffiliates as $affiliate)
                        <?php $Affiliates = \App\Scubaya\model\Affiliations::where('id', $affiliate)->first(['name', 'image']); ?>
                        <div class="affiliations-image-section">
                            <img data-tooltip="{{ ucwords($Affiliates->name) }}" data-inverted=""
                                 alt="{{ $Affiliates->name }}" class="ui tiny image"
                                 src="{{ asset('assets/images/scubaya/affiliations/'.$affiliate.'-'.$Affiliates->image) }}">
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="scu-courses-location-on-map">
            <div id="map"></div>
        </section>
    </div>
@endsection

@section('script-extra')
    @php
        $is_user_logged_in  =   Request::hasCookie('scubaya_dive_in');
        $cart_items         =   0;

        if($is_user_logged_in) {
            $user_id            =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);
        }

        $course_count       =   !$is_user_logged_in?(Request::hasCookie('course')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['course']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'course'], ['status',CHECKOUT_PENDING]])->count();
        $product_count      =   !$is_user_logged_in?(Request::hasCookie('product')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['product']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'product'], ['status',CHECKOUT_PENDING]])->count();
        $hotel_count        =   !$is_user_logged_in?(Request::hasCookie('hotel')?count(unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['hotel']))) :0 ): \App\Scubaya\model\Cart::where([['user_key', $user_id], ['item_type', 'hotel'], ['status',CHECKOUT_PENDING]])->count();

        $cart_items         =   $course_count + $product_count + $hotel_count;

        $clientGeoInfo      =   geoip($_SERVER['REMOTE_ADDR']);
    @endphp

    {{--semantic alert js and css--}}
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/semantic-alert-ui/Semantic-UI-Alert.css')}}">
    <script type="text/javascript" src="{{asset('plugins/semantic-alert-ui/Semantic-UI-Alert.js')}}"></script>

    <script type="text/javascript">
        var total_items_in_cart =   parseInt('{{$cart_items}}');
        var productsInfo        =   JSON.parse('{!! json_encode($productsInfo) !!}');

        function showNotification(data){
            $.uiAlert({
                textHead: '',
                text: data,
                bgcolor: '#48bbd1',
                textcolor: '#fff',
                position: 'top-right',
                icon: 'shopping cart',
                time: 1
            });
        }

        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#course-detail-context'
            });

            $('.ui.dropdown').dropdown();

            initMap();
        });

        $('.show-product-info').click(function () {
            var taxAmount;
            var tax;
            var product_id  = this.id;
            var productInfo = productsInfo[this.id];

            if (productInfo.tax) {
                tax         = productInfo.tax;
                taxAmount   = productInfo.price * ((productInfo.tax) / 100);
            } else {
                tax = 0;
                taxAmount = 0;
            }

            var netAmount = productInfo.price + taxAmount;

            if (productInfo.product_type == '1') {
                var label = '<a class="ui orange label" >Rental</a>';
            } else {
                var label = '<a class="ui olive label" >Sell</a>';
            }

            $('.product.modal #product_name').text(productInfo.title);
            $('.product.modal #product_image').attr('src', 'data:image/' + productInfo.mimeType + ';base64,' + productInfo.image);
            $('.product.modal #manufacturer_by').text('Manufactured By ' + productInfo.manufacturer);
            $('.product.modal #product_description').text(productInfo.description);
            $('.product.modal #product_price').html('<span>' + productInfo.price_symbol + '</span>' + productInfo.price);
            $('.product.modal #product_tax').text(tax + ' %');
            $('.product.modal #product_total').html('<span>' + productInfo.price_symbol + '</span>' + netAmount.toFixed(2));
            $('.product.modal #product_weight').text(productInfo.weight + ' Kg');
            $('.product.modal #product_color').css('background-color', productInfo.color);
            $('.product.modal #product_type').html(label);
            /*$('#product_availability').text('Available').css('color', 'green');*/
            if (productInfo.IE) {
                $('.product.modal .approve').hide();
            } else {
                $('.product.modal .approve').show();
            }

            $('.product.modal').modal({
                closable: true,
                transition: 'horizontal flip',
                onDeny: function () {
                    window.alert('Wait not yet!');
                    return false;
                },
                onApprove: function () {
                    if (!productInfo.IE) {
                        let _token = '{{csrf_token()}}';
                        let url = '{{route('scubaya::checkout::cart::add_to_cart')}}';
                        $.post(url, {type:'product', id: product_id, quantity: 1, _token: _token}, function (data) {
                            if (!data.already) {
                                $('.cart-count').show();
                                $('.cart-count').html('' + ++total_items_in_cart + '');
                                $('.ui.icon.message').click(function(){
                                    window.location = '{{route('scubaya::checkout::cart')}}';
                                });
                            }
                           showNotification(data.status);
                        })
                    } else return true;
                }
            }).modal('show');
        });

        $('.add-to-trip').click(function (e) {
            let course_id       = '{{$course->id}}';
            let no_of_persons   = $(this).parent().parent().closest('div').find('select').val();

            let url = '{{route('scubaya::checkout::cart::add_to_cart')}}';
            let _token = '{{csrf_token()}}';
            $.post(url, {type:'course', course_id: course_id, no_of_persons: no_of_persons, _token: _token}, function (status) {
                if (!status.already) {
                    $('.cart-count').show();
                    $('.cart-count').html('' + ++total_items_in_cart + '');
                }
                showNotification(status.status);
                $('.ui-alert-content-top-right').click(function(){
                    window.location = '{{route('scubaya::checkout::cart')}}';
                });
            });
        });

        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        function initMap() {
            var courses = <?php echo json_encode($courses);?>;

            var map;

            if(courses[0][6] && courses[0][7]) {
                 map    = L.map('map').setView([courses[0][6], courses[0][7]], 4);
            } else {
                 map    = L.map('map').setView([52.370216, 4.895168], 4);
            }


            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                maxZoom: 8,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1Ijoic3VyYmhpMTIzIiwiYSI6ImNqaGtmZjBmcjByczgzN3M4bmRic2t1ZjUifQ.GClBvGXe013hExEMPJTxuA'
            }).addTo(map);

            for (var i = 0; i < courses.length; i++) {
                if(courses[i][6] && courses[i][7]) {
                    var marker   =   L.marker([courses[i][6], courses[i][7]]).addTo(map);

                    var url = '{{route('scubaya::course_details',['--KEY1--', '--DNAME--', '--CNAME--','--KEY2--'])}}';
                    url = url.replace('--KEY1--', courses[i][0]);

                    var centerName    =   courses[i][2];
                    centerName        =   centerName.replace(/\s+/g, '-').toLowerCase();

                    var courseName    =   courses[i][3];
                    courseName        =   courseName.replace(/\s+/g, '-').toLowerCase();

                    url = url.replace('--DNAME--', centerName);
                    url = url.replace('--CNAME--', courseName);
                    url = url.replace('--KEY2--', courses[i][1]);

                    marker.bindPopup(
                        '<div class="info-content">' +
                        '<img width="224" src="data:image/'+courses[i][4]+';base64,'+courses[i][5]+'" />'+
                        '<div class="content">'+
                        '<a href="'+url+'"><h4>'+courses[i][3]+'</h4></a>' +
                        '<p>'+courses[i][8]+'</p>' +
                        '</div>'+
                        '</div>'
                    );
                }
            }
        }
    </script>
@endsection