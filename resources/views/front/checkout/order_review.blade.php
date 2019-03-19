@extends('front.layouts.master')
@section('page-title')
    Divers Information
@endsection
@section('content')
    @include('front._partials.header')
    @php
        use Jenssegers\Agent\Agent;
        $Agent      =   new Agent();
        $total      =   0;
        $minAge     =   0;
        $currency   =   '';
    @endphp
    <section class="cart-header" style="margin-top:14px;height:100px; background: #f4f4f4;">
        <div class="ui grid center aligned">
            @if(!($Agent->isMobile()))
                <div class="thirteen wide column center aligned" style="padding:36px 0px 0px 100px">
                    <div class="fnl-cart-status-bar active">
                        <i class="shopping cart icon"></i>
                            <span>CART</span></a>
                    </div>
                    <div class="fnl-cart-status-bar-strip"></div>
                    <div class="fnl-cart-status-bar fnl-heading1">
                        <i class="edit icon"></i><a href="#">
                            ORDER REVIEW</a>
                    </div>
                    <div class="fnl-cart-status-bar-strip"></div>
                    <div class="fnl-cart-status-bar">
                        <i class="thumbs up icon"></i>
                        DONE
                    </div>
                </div>
                <div class="three wide left aligned column" style="padding-top: 20px">
                    <a href="{{route('scubaya::home')}}">
                        <button class="ui button">Continue Browsing</button>
                    </a>
                </div>
            @else
                <div class="sixteen wide column center aligned" style="padding-top:36px">
                    <a href="{{route('scubaya::home')}}">
                        <button class="ui button">Continue Browsing</button>
                    </a>
                </div>
            @endif
        </div>
    </section>
    <section class="order-review">
    <div class="ui container">
        <form class="ui form" method="post" action="{{route('scubaya::checkout::order_review')}}">
            {{csrf_field()}}
            {{method_field('order_review')}}
            @if(count($courses_in_cart))
            @php
                $noOfPersons    =   0;
                $userAge        =   0;
                $count_course   =   count($courses_in_cart);
            @endphp
            <div class="ui grid segment courses-section">
                <div class="ui sixteen wide column">
                    @foreach($courses_in_cart as $course_in_cart)
                        @php
                            $id                 =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);
                            $user               =   \App\Scubaya\model\User::where('id',$id )->first();
                            $userDob            =   \App\Scubaya\model\UserPersonalInformation::where('user_key',$user->id)->first();

                            if($userDob && key(json_decode($userDob->dob))) {
                                $ageDateDifference  =   date_diff(date_create(key(json_decode($userDob->dob))),date_create(date("Y/m/d")));
                                $userAge            =   $ageDateDifference->format('%y');
                            }

                            $course             =   \App\Scubaya\model\Courses::where('id',$course_in_cart->item_id)->first(['id', 'image',  'course_name','merchant_key','course_pricing','course_days','course_start_date']);
                            $pricing            =   (array)json_decode($course->course_pricing);
                            $course_days        =   json_decode($course->course_days);

                            $minAge             =   $pricing['min_age'];
                            $exchangeRate       =   (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$course->merchant_key))->getExchangeRate();

                            $cart_data          =   json_decode($course_in_cart->item_data);
                            $noOfPersons        =   $cart_data ? $cart_data->no_of_persons : $noOfPersons;
                        @endphp
                        <div class="ui grid" style="margin-bottom:20px;" >

                            <div class="sixteen wide field">
                                <div id="ui grid diver_list">
                                    <div class="row">
                                        <div class="course-image" style="background-image: url('{{ asset('assets/images/scubaya/shop/courses/'.$course->merchant_key.'/'.$course->id.'-'.$course->image) }}')"></div>
                                        <div class="ui blue ribbon label" style="z-index:8">
                                            <i class="bullseye icon"></i> COURSE
                                        </div>
                                        <div class="course-overlay">
                                            <div class="text">{{$course->course_name}}</div>
                                        </div>
                                    </div>
                                    <div class="ui raised segment center aligned course course-information" style="z-index:5">
                                        <div class="ui grid">
                                            <div class="ui five wide column">
                                                <div class="ui grid">
                                                    <div class="ui sixteen wide column">
                                                        <h3 class="course-name-in-cart">Start Date</h3>
                                                        @if($course->course_start_date!=null)
                                                            <span class="">{{$course->course_start_date}}</span>
                                                        @else
                                                            <span>NA</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ui six wide column course-description">
                                                <div class="ui grid">
                                                    <div class="ui sixteen wide column">
                                                        <h3 class="course-name-in-cart">Duration</h3>
                                                        @if($course_days->no_of_days != null)
                                                            <span class="">{{$course_days->no_of_days}} days</span>
                                                        @else
                                                            <span>NA</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ui five wide column" >
                                                <div class="ui grid">
                                                    <div class="ui sixteen wide column">
                                                        <h3 class="course-name-in-cart">Total</h3>
                                                        @if($pricing['price'] != null)
                                                            @php
                                                                $cart_item_price       =    $pricing['price'] * $cart_data->no_of_persons  * $exchangeRate[$course->merchant_key]['rate'];
                                                                $currency              =    isset($exchangeRate[$course->merchant_key]['symbol']) ? $exchangeRate[$course->merchant_key]['symbol'] : '';
                                                                $total                 +=   $cart_item_price;
                                                            @endphp
                                                            <span class="blue subtotal font-weight-900-size-x-large">{{@$exchangeRate[$course->merchant_key]['symbol']}}{{number_format($cart_item_price, 2)}}</span>
                                                        @else
                                                            <span class="blue subtotal font-weight-900-size-x-large">{{ @$exchangeRate[$course->merchant_key]['symbol'].' 0' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($noOfPersons)
                                    <div class="ui row" style="margin-top:30px;padding:0px 20px 0px 20px;">
                                        <h2 class="blue">Divers Information</h2>
                                    </div>
                                    <div class="ui row" style="margin-top:30px;padding:0px 20px 20px 20px;">
                                        @for($no_of_divers = 1; $no_of_divers <= $noOfPersons; $no_of_divers++)
                                            <div class="ui form">
                                                <div class="three fields" style="@if($no_of_divers>1)display:none @endif">
                                                    <div class="field">
                                                        <label for="diver_first_name">First name</label>
                                                        <input type="text"  name="details[{{$course_in_cart->item_id}}][{{$no_of_divers}}][first_name]" value="@if($no_of_divers==1){{decrypt($user->first_name)}}@endif"  @if($no_of_divers==1) readonly @else required @endif>
                                                    </div>
                                                    <div class="field">
                                                        <label>Birth date</label>
                                                        <div class="ui calendar datepicker" >
                                                            <div class="ui input right icon">
                                                                <i class="calendar icon"></i>
                                                                <input type="text" name="details[{{$course_in_cart->item_id}}][{{$no_of_divers}}][birthdate]" @if($userDob) value = "{{key(json_decode($userDob->dob))}}" @endif >
                                                            </div>
                                                        </div>
                                                        <div class="user-min-age-error">
                                                        @if($userAge && $userAge < $pricing['min_age'])
                                                            <p>You are not eligible for this course, Minimum age is {{$pricing['min_age']}}.</p>
                                                        @endif
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label for="user_id">User ID</label>
                                                        <input type="text" name="details[{{$course_in_cart->item_id}}][{{$no_of_divers}}][user_id]" value="@if($no_of_divers==1){{$user->UID}}@endif" @if($no_of_divers==1) onfocus="showAllfields()" readonly @endif>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($products_in_cart))
                @php
                    $product_count  =   count($products_in_cart);
                @endphp
                <div class="ui grid raised segment products-section">
                    <div class="ui sixteen wide column">
                        <div class="ui black ribbon label">
                            <i class="product hunt icon"></i> @if($product_count > 1) PRODUCTS @else PRODUCT @endif
                        </div>
                        @if(count($products_in_cart))
                            @foreach($products_in_cart as $product_in_cart)
                                @php
                                    $id             =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);
                                    $user           =   \App\Scubaya\model\User::where('id',$id )->first();
                                    $product        =   \App\Scubaya\model\Products::where('id',$product_in_cart->item_id)->first();
                                @endphp
                                    <div class="ui grid">
                                        <div class="ui four wide column">
                                            <img style="width: 100%"
                                                 src="{{asset('assets/images/scubaya/shop/products/'.$product->merchant_key.'/'.$product->id.'-'.$product->product_image)}}"
                                                 alt="{{$product->title}}"/>
                                        </div>

                                        <div class="ui four wide column product-description">
                                            <div class="ui grid">
                                                <div class="ui sixteen wide column">
                                                    <span class="product-name">{{$product->title}}</span><br>
                                                    @if(!empty($product->manufacturer))
                                                        <div class="meta">Manufactured By {{$product->manufacturer}}</div>
                                                    @endif

                                                    @if($product->product_type == 1)
                                                        <div class="ui green horizontal label">Rental</div>
                                                    @else
                                                        <div class="ui yellow horizontal label">Sell</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="ui row">
                                                    <div class="ui seven wide column">
                                                        <span class="cart-item-label">Weight:</span>
                                                    </div>
                                                    <div class="ui eight wide column">
                                                        <span>{{$product->weight}} Kg</span>
                                                    </div>
                                                </div>

                                                <div class="ui row">
                                                    <div class="ui seven wide column">
                                                        <span class="cart-item-label">Color:</span>
                                                    </div>
                                                    <div class="ui eight wide column">
                                                        <span id="product_color" style="background-color: {{ $product->color }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $quantity = json_decode($product_in_cart->item_data)->quantity;

                                            if($product->tax) {
                                                $product->price  =   $product->price + ( $product->price * ($product->tax / 100) );
                                            }

                                            $exchangeRate          =    (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$product->merchant_key))->getExchangeRate();
                                        @endphp

                                        <div class="ui four wide center aligned column border-left-with-height cart-price-section">
                                            <div class="ui grid">
                                                <div class="ui seven wide column">
                                                    <p><strong>Quantity</strong></p>
                                                    <div><h4>{{$quantity}}</h4></div>
                                                </div>
                                                <div class="ui eight wide column">
                                                    <h2>{{@$exchangeRate[$product->merchant_key]['symbol']}}{{number_format(($product->price * $exchangeRate[$product->merchant_key]['rate']), 2)}}</h2>
                                                    <span class="tariff-pricing-label">Price</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui four wide center aligned column border-left-with-height cart-total-section">
                                            <div>
                                                <span class="font-weight-800-size-17px">Total</span>
                                                <h2 class="blue subtotal">{{@$exchangeRate[$product->merchant_key]['symbol']}}{{number_format(($quantity * $product->price * $exchangeRate[$product->merchant_key]['rate']), 2)}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                @php
                                    $currency   =   isset($exchangeRate[$product->merchant_key]['symbol']) ? $exchangeRate[$product->merchant_key]['symbol'] : '';
                                    $total      =   $total + ( ($quantity) * ($product->price) * $exchangeRate[$product->merchant_key]['rate'] )
                                @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            @if(count($hotels_in_cart))
                <div class="ui grid raised segment hotels-section">
                    <div class="ui sixteen wide column">
                        <div class="ui black ribbon label">
                            <i class="hotel hunt icon"></i> @if(count($hotels_in_cart) > 1) HOTELS @else HOTEL @endif
                        </div>
                        @foreach($hotels_in_cart as $hotel_in_cart)
                            <?php
                                $pppn           =   false;

                                $userId         =   \Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['scubaya_dive_in']);

                                $user           =   \App\Scubaya\model\User::where('id',$userId )->first();

                                $hotel          =   \App\Scubaya\model\RoomPricing::where('room_pricing.id', '=', $hotel_in_cart->item_id)
                                                                            ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                                                            ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                                                            ->select('hotel.name', 'hotel.address', 'hotel.id', 'hotel.merchant_primary_id', 'room_pricing.tariff_title', 'room_pricing.additional_tariff_data', 'room_details.id as room_id', 'room_details.type', 'room_details.room_image')
                                                                            ->first();


                                $tariffData     =   (array)json_decode($hotel->additional_tariff_data);

                                if(array_key_exists('micro', $tariffData)) {
                                    if($tariffData['micro']->ignore_pppn) {
                                        $maxPeople  =   $tariffData['micro']->max_people;
                                        $label      =   'Per Night';
                                    } else {
                                        $maxPeople  =   $tariffData['micro']->max_people;
                                        $pppn       =   true;
                                        $label      =   'Per Person/Night';
                                    }
                                }

                                if(array_key_exists('advance', $tariffData)) {
                                    if($tariffData['advance']->ignore_pppn) {
                                        $maxPeople  =   $tariffData['advance']->max_people;
                                        $label      =   'Per Night';
                                    } else {
                                        $maxPeople  =   $tariffData['advance']->max_people;
                                        $pppn       =   true;
                                        $label      =   'Per Person/Night';
                                    }
                                }

                                if(array_key_exists('normal', $tariffData)) {
                                    /* To check global per person per night option */
                                    $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $hotel->merchant_primary_id)
                                        ->first(['currency']);
                                    $pricingSetting  =   json_decode($pricingSetting->currency);

                                    /* If Price per person per night option is set to yes globally
                                     * then include person and night in price calculation
                                     * else include nights only.
                                     */
                                    if($pricingSetting->prices_pppn) {
                                        $maxPeople   =   $tariffData['normal']->max_people;
                                        $pppn        =   true;
                                        $label       =   'Per Person/Night';
                                    } else {
                                        $maxPeople   =   $tariffData['normal']->max_people;
                                        $label       =   'Per Night';
                                    }
                                }
                            ?>
                            <div class="ui grid">
                                <div class="ui four wide column">
                                    <img style="width: 100%"
                                         src="{{asset('assets/images/scubaya/rooms/'.$hotel->room_id.'-'.$hotel->room_image)}}"
                                         alt="{{$hotel->name}}"/>
                                </div>

                                <?php
                                $check_in   =    json_decode($hotel_in_cart->item_data)->check_in;
                                $check_out  =    json_decode($hotel_in_cart->item_data)->check_out;
                                $price      =    json_decode($hotel_in_cart->item_data)->price;
                                $persons    =    json_decode($hotel_in_cart->item_data)->no_of_persons;

                                $exchangeRate   =    (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$hotel->merchant_primary_id))->getExchangeRate();
                                ?>

                                <div class="ui four wide column hotel-description">
                                    <div class="ui grid">
                                        <div class="ui sixteen wide column">
                                            <div class="hotel-name">{{$hotel->name}}</div>
                                            <div class="tariff-title">{{ ucwords($hotel->tariff_title) }}</div>
                                            <div class="ui red horizontal label">{{ $hotel->type }}</div>
                                        </div>
                                    </div>
                                    <div class="ui grid">
                                        <div class="ui row">
                                            <div class="ui seven wide column">
                                                <span class="cart-item-label">Check In:</span>
                                            </div>
                                            <div class="ui eight wide column">
                                                <span>{{$check_in}}</span>
                                            </div>
                                        </div>
                                        <div class="ui row">
                                            <div class="ui seven wide column">
                                                <span class="cart-item-label">Check Out:</span>
                                            </div>
                                            <div class="ui eight wide column">
                                                <span>{{$check_out}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ui four wide center aligned column border-left-with-height cart-price-section">
                                    @if($persons > 3)
                                        <i class="fa fa-user users-in-room"></i> x {{ $persons }}
                                    @else
                                        @for($i = 0; $i < $persons; $i++)
                                            <i class="fa fa-user users-in-room"></i>
                                        @endfor
                                    @endif
                                    <h2>{{@$exchangeRate[$hotel->merchant_primary_id]['symbol']}}{{number_format($price * $exchangeRate[$hotel->merchant_primary_id]['rate'], 2)}}</h2>
                                    <span class="tariff-pricing-label">{{ $label }}</span>
                                </div>

                                @php
                                    if($pppn) {
                                        $netAmount  =   $price * $exchangeRate[$hotel->merchant_primary_id]['rate'] * $persons;
                                    } else {
                                        $netAmount  =   $price * $exchangeRate[$hotel->merchant_primary_id]['rate'];
                                    }
                                @endphp

                                <div class="ui four wide center aligned column border-left-with-height cart-total-section">
                                    <div>
                                        <span class="font-weight-800-size-17px">Total</span>
                                        <h2 class="blue subtotal">{{@$exchangeRate[$hotel->merchant_primary_id]['symbol']}}{{ number_format($netAmount, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                            @php
                                $currency   =   isset($exchangeRate[$hotel->merchant_primary_id]['symbol']) ? $exchangeRate[$hotel->merchant_primary_id]['symbol'] : '';
                                $total      +=  $netAmount * $exchangeRate[$hotel->merchant_primary_id]['rate'];
                            @endphp
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="ui grid" style="margin:20px">
                <div class="row">
                    <div class="ui sixteen wide column right aligned">
                        <button class="ui animated fade big green button" tabindex="0" type="submit" id="submit_button">
                            <div class="visible content">Confirm Booking</div>
                            <div class="hidden content">
                                TOTAL = {{@$currency}}{{ number_format($total, 2) }}
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </section>
@endsection
@section('script-extra')
    <script type="text/javascript">

        var courseRequriedAge = <?php echo $minAge; ?> ;

        $('.datepicker').calendar({
            type: 'date',
            onChange: function(date) {
                var year    = date.getFullYear();
                var userAge = (new Date()).getFullYear()-year ;

                if(userAge < courseRequriedAge){
                    var errorHtml = '<p>You are not eligible for this course, Minimum age is ' + courseRequriedAge +'.</p>' ;
                    $('.user-min-age-error').find('p').remove();
                    $('.user-min-age-error').append(errorHtml);

                    $('#submit_button').attr('disabled', 'disabled');
                }
                else{
                    $('.user-min-age-error').find('p').remove();
                    $('#submit_button').removeAttr('disabled', 'disabled');
                }
            },
        });

        $('#submit_button').click(function(){
           showAllfields();
        });

        function showAllfields(){
            if($('.three.fields').is(':hidden')){
                $('.three.fields').transition('fade in');
            }
        }

    </script>
@endsection