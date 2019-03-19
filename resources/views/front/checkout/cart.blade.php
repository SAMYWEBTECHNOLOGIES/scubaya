@extends('front.layouts.master')
@section('page-title')
    Cart
@endsection
@section('content')
    @include('front._partials.header')
    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
        $total =   0;
        $currency   =   '';
        $courses    =   [];
        $products   =   [];
        $hotels     =   [];
        $total_for_courses          =   0;
        $total_for_products         =   0;
        $total_for_hotels           =   0;
        $grand_total                =   0;

        /*prices of course which are in cart*/
        $course_with_prices         =   [];
        $product_with_prices        =   [];
        $hotel_with_prices          =   [];

        /*all the courses with no of people*/
        $course_with_min_people   =   [];

        $isUserLoggedIn     =   Request::hasCookie('scubaya_dive_in');

        if($isUserLoggedIn){
            $user_id            =   Crypt::decrypt($_COOKIE['scubaya_dive_in']);

            /* fetch courses, products and hotels in the cart if available */
            $courses      =   \App\Scubaya\model\Cart::where([['user_key',$user_id], ['item_type', 'course'], ['status',CHECKOUT_PENDING]])->get();
            $products     =   \App\Scubaya\model\Cart::where([['user_key',$user_id], ['item_type', 'product'], ['status',CHECKOUT_PENDING]])->get();
            $hotels       =   \App\Scubaya\model\Cart::where([['user_key',$user_id], ['item_type', 'hotel'], ['status',CHECKOUT_PENDING]])->get();

        } else {

            $cart   =   Request::hasCookie('course') ? unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['course'])) : [];
            if(!empty($cart)){
                foreach ($cart as $item    =>  $data){
                    $course                 =   new stdClass();
                    /*$course->id             =   $item;*/
                    $course->item_id        =   $item;
                    $course->no_of_people   =   $data['no_of_persons'];
                    array_push($courses,$course);
                }
            }

            $cart   =   Request::hasCookie('product') ? unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['product'])) : [];
            if(!empty($cart)){
                foreach ($cart as $item   =>  $data){
                    $product                 =   new stdClass();
                    /*$product->id             =   $item;*/
                    $product->item_id        =   $item;
                    $product->quantity       =   $data['quantity'];
                    array_push($products,$product);
                }
            }

            $cart   =   Request::hasCookie('hotel') ? unserialize(\Illuminate\Support\Facades\Crypt::decrypt($_COOKIE['hotel'])) : [];
            if(!empty($cart)){
                foreach ($cart as $item   =>  $data){
                    $hotel                 =   new stdClass();
                    /*$hotel->id             =   $item;*/
                    $hotel->item_id        =   $item;
                    $hotel->checkin        =   $data['check_in'];
                    $hotel->checkout       =   $data['check_out'];
                    $hotel->price          =   $data['price'];
                    $hotel->no_of_persons  =   $data['no_of_persons'];
                    array_push($hotels,$hotel);
                }
            }
        }

    @endphp

    <div id="cart-context">
        <section class="cart-header">
            <div class="ui grid center aligned">
                @if(!($Agent->isMobile()))
                    <div class="thirteen wide column center aligned" style="padding:36px 0px 0px 100px">
                        <div class="fnl-cart-status-bar active fnl-heading1">
                            <i class="shopping cart icon"></i>
                            <a href="#"><span>CART</span></a>
                        </div>
                        <div class="fnl-cart-status-bar-strip"></div>
                        <div class="fnl-cart-status-bar">
                            <i class="edit icon"></i>
                            REVIEW YOUR REQUEST
                        </div>
                        <div class="fnl-cart-status-bar-strip"></div>
                        <div class="fnl-cart-status-bar">
                            <i class="thumbs up icon"></i>
                            CONFIRM REQUEST
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
        @if (session()->has('already_exists'))
            <section class="already-exists">
                <div class="ui container cart-table">
                    <div class="ui grid">
                        <div class="six wide column">
                            <div class="ui floating message">
                                <p>{{session('already_exists')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        @if(count($courses) || count($products) || count($hotels))
            @if(count($courses))
                <div class="ui basic modal delete-course-modal">
                    <div class="ui icon header">
                        <i class="trash icon"></i>
                        Delete this Course
                    </div>
                    <div class="content">
                        <span style="font-size:x-large;">Think twice before doing it. You may miss the extreme adventure!<p>Are you sure to delete it? </p></span>
                    </div>
                    <div class="actions">
                        <div class="ui red cancel inverted button">
                            <i class="remove icon"></i>
                            No
                        </div>
                        <div class="ui green ok inverted button">
                            <i class="checkmark icon"></i>
                            Yes
                        </div>
                    </div>
                </div>

                <section class="courses-section" style="margin-top:20px">
                    <div class="ui container">
                        <h2>Courses</h2>
                    </div>
                    <div class="ui grid container">
                        @foreach($courses as $item)
                            @php
                                $course_detail      =   \App\Scubaya\model\Courses::where('id',$item->item_id)->first(['course_name','merchant_key','description','course_pricing','image','course_days','course_start_date','course_end_date']);
                                $pricing            =   (array)json_decode($course_detail->course_pricing);
                                $course_days        =   json_decode($course_detail->course_days);
                                $min_people         =   isset($pricing['min_people'])?(int)$pricing['min_people']:1;
                                $max_people         =   isset($pricing['max_people'])?(int)$pricing['max_people']:5;

                                $exchangeRate       =   new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$course_detail->merchant_key);
                                $exchangeRate       =   $exchangeRate->getExchangeRate();

                                $course_with_prices[$item->item_id] = ($pricing['price']) * $exchangeRate[$course_detail->merchant_key]['rate'];
                            @endphp
                            <div class="sixteen wide column">
                                <form method="post" action="{{ route('scubaya::checkout::hotel::delete_hotel_item') }}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="type" value="course">
                                    <input type="hidden" name="id" value="{{ $item->item_id }}">
                                    <button type="submit" class="floating ui label delete-course-item" >X</button>
                                </form>
                                <div class="ui raised segment">
                                    <div class="ui grid">
                                        <div class="ui four wide column">
                                            <img style="width: 100%"
                                                 src="@if($course_detail->image) {{asset('assets/images/scubaya/shop/courses/'.$course_detail->merchant_key.'/'.$item->item_id.'-'.$course_detail->image)}}
                                                      @else {{ asset('assets/images/default.png') }}
                                                      @endif"
                                                 alt="{{$course_detail->course_name}}"/>
                                        </div>
                                        <div class="ui four wide column course-description">
                                            <div class="ui grid">
                                                <div class="ui sixteen wide column">
                                                    <span class="course-name">{{$course_detail->course_name}}</span>
                                                </div>
                                            </div>

                                            @if($course_days->no_of_days || $course_detail->course_start_date || $course_detail->course_end_date)
                                            <div class="ui grid">
                                                <div class="ui row">
                                                    @if($course_days->no_of_days)
                                                        <div class="ui seven wide column">
                                                            <span class="cart-item-label">Duration :</span>
                                                        </div>
                                                        <div class="ui eight wide column">
                                                            <span>{{$course_days->no_of_days.' days'}}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($course_detail->course_start_date)
                                                    <div class="ui row">
                                                        <div class="ui seven wide column">
                                                            <span class="cart-item-label">Starting Date:</span>
                                                        </div>
                                                        <div class="ui eight wide column">
                                                            <span>{{$course_detail->course_start_date}}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($course_detail->course_end_date)
                                                    <div class="ui row">
                                                        <div class="ui seven wide column">
                                                            <span class="cart-item-label">Ending Date:</span>
                                                        </div>
                                                        <div class="ui eight wide column">
                                                            <span>{{$course_detail->course_end_date}}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>

                                        <?php
                                        $people   =   isset($item->item_data) ? json_decode($item->item_data)->no_of_persons : $item->no_of_people ;
                                        ?>

                                        <div class="ui four wide center aligned column border-left-with-height cart-price-section">
                                            <div class="ui grid">
                                                <div class="ui seven wide column">
                                                    {{--<span class="font-weight-800-size-17px">People</span>--}}
                                                    <i class="fa fa-users fa-lg" aria-hidden="true"></i> <br>
                                                    <select class="ui search dropdown no_of_persons"
                                                            data-courseid="{{$item->item_id}}" name="no_of_persons"
                                                            style="min-width:unset !important; width:50%;">
                                                        @for($i =   $min_people;    $i  <=  $max_people;    $i++)
                                                            <option value="{{$i}}" @if($people == $i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="ui eight wide column">
                                                    <h2>{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}{{number_format((($pricing['price']) * $exchangeRate[$course_detail->merchant_key]['rate']), 2)}}</h2>
                                                    <span class="tariff-pricing-label">Price Per Person</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui four wide center aligned column border-left-with-height cart-total-section">
                                            <div>
                                                <span class="font-weight-800-size-17px">Total</span>
                                                <h2 class="blue subtotal">{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}{{  number_format($people * $pricing['price'] * $exchangeRate[$course_detail->merchant_key]['rate'], 2) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $total_for_courses  +=  ( $pricing['price'] * $exchangeRate[$course_detail->merchant_key]['rate'] * $people );
                                $currency           =   isset($exchangeRate[$course_detail->merchant_key]['symbol']) ? $exchangeRate[$course_detail->merchant_key]['symbol'] : '';
                            @endphp
                        @endforeach
                    </div>
                </section>
            @endif
            @if(count($products))
                <div class="ui basic modal delete-product-modal">
                    <div class="ui icon header">
                        <i class="trash icon"></i>
                        Delete this Product
                    </div>
                    <div class="content">
                        <span style="font-size:x-large;"><p> Are you sure to delete this Product? </p></span>
                    </div>
                    <div class="actions">
                        <div class="ui red cancel inverted button">
                            <i class="remove icon"></i>
                            No
                        </div>
                        <div class="ui green ok inverted button">
                            <i class="checkmark icon"></i>
                            Yes
                        </div>
                    </div>
                </div>
                <section class="products-section" style="margin-top:20px">
                    <div class="ui container">
                        <h2>Products</h2>
                    </div>
                    <div class="ui grid container">
                        @foreach($products as $product)
                            @php
                                $product_detail     =   \App\Scubaya\model\Products::where('id',$product->item_id)->first(['merchant_key','title', 'tax', 'manufacturer','price','product_image', 'weight', 'color', 'product_type']);
                                $price              =   $product_detail->price;

                                $exchangeRate       =   new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$product_detail->merchant_key);
                                $exchangeRate       =   $exchangeRate->getExchangeRate();
                            @endphp
                            <div class="sixteen wide column">
                                <form method="post" action="{{route('scubaya::checkout::products::delete_product_item')}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="type" value="product">
                                    <input type="hidden" name="id" value="{{ $product->item_id }}">
                                    <button type="submit" class="floating ui label delete-product-item" >X</button>
                                </form>
                                <div class="ui raised segment">
                                    <div class="ui grid">
                                        <div class="ui four wide column">
                                            <img style="width: 100%"
                                                 src="{{asset('assets/images/scubaya/shop/products/'.$product_detail->merchant_key.'/'.$product->item_id.'-'.$product_detail->product_image)}}"
                                                 alt="{{$product_detail->title}}"/>
                                        </div>

                                        <div class="ui four wide column product-description">
                                            <div class="ui grid">
                                                <div class="ui sixteen wide column">
                                                    <span class="product-name">{{$product_detail->title}}</span><br>
                                                    @if(!empty($product_detail->manufacturer))
                                                        <div class="meta">Manufactured By {{ ucwords($product_detail->manufacturer) }}</div>
                                                    @endif

                                                    @if($product_detail->product_type == 1)
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
                                                        <span>{{$product_detail->weight}} Kg</span>
                                                    </div>
                                                </div>

                                                <div class="ui row">
                                                    <div class="ui seven wide column">
                                                        <span class="cart-item-label">Color:</span>
                                                    </div>
                                                    <div class="ui eight wide column">
                                                        <span id="product_color" style="background-color: {{ $product_detail->color }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $quantity   =   isset($product->item_data) ? json_decode($product->item_data)->quantity : $product->quantity ;
                                        ?>

                                        <div class="ui four wide center aligned column border-left-with-height cart-price-section">
                                            <div class="ui grid">
                                                <div class="ui seven wide column">
                                                    <p><strong>Quantity</strong></p>
                                                    <div>
                                                        <select class="ui search dropdown product-quantity"
                                                                data-product_id="{{$product->item_id}}" name="quantity"
                                                                style="min-width:unset !important; width:50%;">
                                                            @for($i=1;$i<=10;$i++)
                                                                <option value="{{$i}}" @if($quantity == $i) selected @endif>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                @php
                                                    if($product_detail->tax) {
                                                        $price  =   $price + ( $price * ($product_detail->tax / 100) );
                                                    }

                                                    $product_with_prices[$product->item_id] =   $price * $exchangeRate[$product_detail->merchant_key]['rate'];
                                                @endphp

                                                <div class="ui eight wide column">
                                                    <h2>{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}{{number_format(($price * $exchangeRate[$product_detail->merchant_key]['rate']), 2)}}</h2>
                                                    <span class="tariff-pricing-label">Price</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui four wide center aligned column border-left-with-height cart-total-section">
                                            <div>
                                                <span class="font-weight-800-size-17px">Total</span>
                                                <h2 class="blue subtotal">{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}{{number_format(($quantity * $price * $exchangeRate[$product_detail->merchant_key]['rate']), 2)}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $total_for_products  +=  ($quantity * $price * $exchangeRate[$product_detail->merchant_key]['rate']);
                                $currency            =   isset($exchangeRate[$product_detail->merchant_key]['symbol']) ? $exchangeRate[$product_detail->merchant_key]['symbol'] : '';
                            @endphp
                        @endforeach
                    </div>
                </section>
            @endif
            @if(count($hotels))
                <div class="ui basic modal delete-hotel-modal">
                    <div class="ui icon header">
                        <i class="trash icon"></i>
                        Delete this Course
                    </div>
                    <div class="content">
                        <span style="font-size:x-large;">Think twice before doing it. You may miss the extreme adventure!<p>Are you sure to delete it? </p></span>
                    </div>
                    <div class="actions">
                        <div class="ui red cancel inverted button">
                            <i class="remove icon"></i>
                            No
                        </div>
                        <div class="ui green ok inverted button">
                            <i class="checkmark icon"></i>
                            Yes
                        </div>
                    </div>
                </div>

                <section class="hotels-section" style="margin-top:20px">
                    <div class="ui container">
                        <h2>Hotels</h2>
                    </div>
                    <div class="ui grid container">
                        @foreach($hotels as $hotel)
                            @php
                                $pppn            =   false;

                                $hotel_detail    =   \App\Scubaya\model\RoomPricing::where('room_pricing.id', '=', $hotel->item_id)
                                                                            ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                                                            ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                                                            ->select('hotel.name', 'hotel.address', 'hotel.id', 'hotel.merchant_primary_id', 'room_pricing.tariff_title', 'room_pricing.additional_tariff_data', 'room_details.id as room_id', 'room_details.type', 'room_details.room_image')
                                                                            ->first();

                                $exchangeRate    =   new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$hotel_detail->merchant_primary_id);
                                $exchangeRate    =   $exchangeRate->getExchangeRate();

                                $tariffData      =   (array)json_decode($hotel_detail->additional_tariff_data);

                                if(array_key_exists('micro', $tariffData)) {
                                    if($tariffData['micro']->ignore_pppn) {
                                        $maxPeople  =   $tariffData['micro']->max_people;
                                        $minPeople  =   $tariffData['micro']->min_people;
                                        $label      =   'Per Night';
                                    } else {
                                        $maxPeople  =   $tariffData['micro']->max_people;
                                        $minPeople  =   $tariffData['micro']->min_people;
                                        $pppn       =   true;
                                        $label      =   'Per Person/Night';
                                    }
                                }

                                if(array_key_exists('advance', $tariffData)) {
                                    if($tariffData['advance']->ignore_pppn) {
                                        $maxPeople  =   $tariffData['advance']->max_people;
                                        $minPeople  =   $tariffData['advance']->min_people;
                                        $label      =   'Per Night';
                                    } else {
                                        $maxPeople  =   $tariffData['advance']->max_people;
                                        $minPeople  =   $tariffData['advance']->min_people;
                                        $pppn       =   true;
                                        $label      =   'Per Person/Night';
                                    }
                                }

                                if(array_key_exists('normal', $tariffData)) {
                                    /* To check global per person per night option */
                                    $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $hotel_detail->merchant_primary_id)
                                                                                                ->first(['currency']);
                                    $pricingSetting  =   json_decode($pricingSetting->currency);

                                    /* If Price per person per night option is set to yes globally
                                     * then include person and night in price calculation
                                     * else include nights only.
                                     */
                                    if($pricingSetting->prices_pppn) {
                                        $maxPeople   =   $tariffData['normal']->max_people;
                                        $minPeople   =   $tariffData['normal']->min_people;
                                        $pppn        =   true;
                                        $label       =   'Per Person/Night';
                                    } else {
                                        $maxPeople   =   $tariffData['normal']->max_people;
                                        $minPeople   =   $tariffData['normal']->min_people;
                                        $label       =   'Per Night';
                                    }
                                }
                            @endphp
                            <div class="sixteen wide column">
                                <form method="post" action="{{ route('scubaya::checkout::hotel::delete_hotel_item') }}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="type" value="hotel">
                                    <input type="hidden" name="id" value="{{ $hotel->item_id }}">
                                    <button type="submit" class="floating ui label delete-hotel-item" >X</button>
                                </form>
                                <div class="ui raised segment">
                                    <div class="ui grid">
                                        <div class="ui four wide column">
                                            <img style="width: 100%"
                                                 src="{{asset('assets/images/scubaya/rooms/'.$hotel_detail->room_id.'-'.$hotel_detail->room_image)}}"
                                                 alt="{{$hotel_detail->name}}"/>
                                        </div>

                                        <?php
                                        $check_in   =   isset($hotel->item_data) ? json_decode($hotel->item_data)->check_in : $hotel->checkin ;
                                        $check_out  =   isset($hotel->item_data) ? json_decode($hotel->item_data)->check_out : $hotel->checkout ;
                                        $price      =   isset($hotel->item_data) ? json_decode($hotel->item_data)->price : $hotel->price;
                                        $hotel_with_prices[$hotel->item_id]    =   $price * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'];
                                        ?>

                                        <div class="ui four wide column hotel-description">
                                            <div class="ui grid">
                                                <div class="ui sixteen wide column">
                                                    <div class="hotel-name">{{$hotel_detail->name}}</div>
                                                    <div class="tariff-title">{{ ucwords($hotel_detail->tariff_title) }}</div>
                                                    <div class="ui red horizontal label">{{ $hotel_detail->type }}</div>
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

                                        <?php
                                        $people   =   isset($hotel->item_data) ? json_decode($hotel->item_data)->no_of_persons : $hotel->no_of_persons ;
                                        ?>

                                        <div class="ui four wide center aligned column border-left-with-height cart-price-section">
                                            <div class="ui grid">
                                                <div class="ui seven wide column">
                                                    {{--<span class="font-weight-800-size-17px">People</span>--}}
                                                    <i class="fa fa-users fa-lg" aria-hidden="true"></i> <br>
                                                    <select class="ui search dropdown no_of_persons"
                                                            data-tariffid="{{$hotel->item_id}}"
                                                            data-pppn="{{ $pppn ? 1 : 0 }}"
                                                            name="no_of_persons"
                                                            style="min-width:unset !important; width:50%;">
                                                        @for($i =   $minPeople;    $i  <=  $maxPeople;    $i++)
                                                            <option value="{{$i}}" @if($people == $i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="ui eight wide column">
                                                    <h2>{{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{number_format(($price * $exchangeRate[$hotel_detail->merchant_primary_id]['rate']), 2)}}</h2>
                                                    <span class="tariff-pricing-label">{{ $label }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            if($pppn) {
                                                $netAmount  =   $price * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'] * $people;
                                            } else {
                                                $netAmount  =   $price * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'];
                                            }
                                        @endphp

                                        <div class="ui four wide center aligned column border-left-with-height cart-total-section">
                                            <div>
                                                <span class="font-weight-800-size-17px">Total</span>
                                                <h2 class="blue subtotal">{{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{number_format($netAmount , 2)}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $total_for_hotels       +=  ($netAmount) * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'];
                                $currency               =   isset($exchangeRate[$hotel_detail->merchant_primary_id]['symbol']) ? $exchangeRate[$hotel_detail->merchant_primary_id]['symbol'] : '';
                            @endphp
                        @endforeach
                    </div>
                </section>
            @endif
            @php
                $grand_total =  $total_for_courses + $total_for_products + $total_for_hotels;
            @endphp
            <section class="order-total">
                <div class="ui container">
                    <div class="ui grid">
                        <div class="ui sixteen wide column right aligned">
                            <div class="ui right aligned grid">
                                <div class="right floated left aligned six wide column order-summary">
                                    <div class="" style="margin-top:20px;">
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <h2>ORDER SUMMARY</h2>
                                            </div>
                                        </div>
                                        @if(count($courses))
                                            <div class="ui grid">
                                                <div class="ui eight wide column">
                                                    <span>Courses</span>
                                                </div>
                                                <div class="ui eight wide right aligned column">
                                                    <span id="total_for_courses" class="">
                                                        <strong>{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}{{number_format($total_for_courses, 2)}}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if(count($products))
                                            <div class="ui grid">
                                                <div class="ui eight wide column">
                                                    <span>Products</span>
                                                </div>
                                                <div class="ui eight wide right aligned column">
                                                    <span id="total_for_products" class="">
                                                        <strong>{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}{{ number_format($total_for_products, 2) }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if(count($hotels))
                                            <div class="ui grid">
                                                <div class="ui eight wide column">
                                                    <span>Hotels</span>
                                                </div>
                                                <div class="ui eight wide right aligned column">
                                                    <span id="total_for_hotels">
                                                        <strong>{{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{ number_format($total_for_hotels, 2) }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <hr/>
                                        <div class="ui grid">
                                            <div class="ui eight wide column">
                                                <span class="font-weight-700-size-12px">Grand Total</span>
                                            </div>
                                            <div class="ui eight wide right aligned column">
                                                <span class="font-weight-700-size-12px" id="grand_total">{{@$currency}}{{number_format($grand_total, 2)}}</span>
                                            </div>
                                        </div>
                                        <div class="ui grid">
                                            <div class="ui eight wide column">

                                            </div>
                                            <div class="ui eight wide column">
                                                <form action="{{route('scubaya::checkout::order_review')}}" method="post">
                                                    {{csrf_field()}}
                                                    <button type="submit" id="submit_button"
                                                            class="ui right floated green labeled icon button"><i
                                                                class="right arrow icon"></i>Send Booking Request
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="cart-empty" style="margin:60px 20px 60px 20px">
                <div class="ui one column stackable center aligned page grid">
                    <div class="ui column eight wide compact segment">
                        <h2 class="text-center">Your Cart is Empty!</h2>
                    </div>
                </div>
            </section>
        @endif

        <section class="services-section">
            <div class="ui container">
                <div class="ui three column stackable center aligned grid">
                    <div class="column">
                        <i class="map marker alternate icon"></i>
                        <h3 class="text-center">Easily reachable</h3>
                    </div>

                    <div class="column">
                        <i class="shield alternate icon"></i>
                        <h3 class="text-center">Authenticity</h3>
                    </div>

                    <div class="column">
                        <i class="life ring icon"></i>
                        <h3 class="text-center">Explore Adventure</h3>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script-extra')
    {{--semantic alert js and css--}}
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/semanctic-alert-ui/Semantic-UI-Alert.css')}}">
    <script type="text/javascript" src="{{asset('plugins/semanctic-alert-ui/Semantic-UI-Alert.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#cart-context'
            });
        });

        /*every course price which is in cart*/
        var course_prices           =       JSON.parse('{!! json_encode($course_with_prices) !!}');
        var product_prices          =       JSON.parse('{!! json_encode($product_with_prices) !!}');
        var hotel_prices            =       JSON.parse('{!! json_encode($hotel_with_prices) !!}');
        var course_with_min_people  =       JSON.parse('{!! json_encode($course_with_min_people) !!}');


        $('.delete-course-item,.delete-product-item, .delete-hotel-item').click(function(e){
            e.preventDefault();
            var this_variable   =   this;
            var class_clicked   =   '';

            if($(this).hasClass('delete-course-item')) {
                class_clicked   =   '.delete-course-modal';
            }

            if($(this).hasClass('delete-product-item')) {
                class_clicked   =   '.delete-product-modal';
            }

            if($(this).hasClass('delete-hotel-item')) {
                class_clicked   =   '.delete-hotel-modal';
            }

            $(class_clicked)
                .modal({
                    closable  : false,
                    onDeny    : function(){
                        return true;
                    },
                    onApprove : function(e) {
                        jQuery(this_variable).parent().submit();
                    }
                })
                .modal('show')
            ;
        });

        cookie_present = '{{Request::hasCookie('scubaya_dive_in')?1:0}}';

        $('.special.cards .image').dimmer({
            on: 'hover'
        });
        $('.compact.segment').transition('scale');
        $('.compact.segment').transition({
            animation: 'jiggle',
            duration: 800,
            interval: 2000
        });
        $('.column .icon').hover(function () {
            $(this).transition('set looping').transition('pulse', '200ms');
        }, function () {
            $(this)
                .transition('remove looping');
        });

        /*change in total*/
        function changeTotal() {
            var total_for_courses   = 0;
            var total_for_products  = 0;
            var total_for_hotels    = 0;
            var grand_total         = 0;

            $(".courses-section .no_of_persons").each(function (index) {
                var selected        =   $(this).find('select').val();
                total_for_courses   +=  selected * course_prices[$(this).find('select').data('courseid')];
            });

            $(".product-quantity").each(function (index) {
                var selected        =   $(this).find('select').val();
                total_for_products  +=  selected * product_prices[$(this).find('select').data('product_id')];
            });

            $(".hotels-section .no_of_persons").each(function (index) {
                var pppn             =   $(this).find('select').data('pppn');
                var selected        =   $(this).find('select').val();

                if(pppn) {
                    total_for_hotels    +=  selected * hotel_prices[$(this).find('select').data('tariffid')];
                } else {
                    total_for_hotels    +=  hotel_prices[$(this).find('select').data('tariffid')];
                }

            });

            $('#total_for_courses').html(' <strong>{{@$currency}}' + total_for_courses.toFixed(2) + '</strong>');
            $('#total_for_products').html(' <strong>{{@$currency}}' + total_for_products.toFixed(2) + '</strong>');
            $('#total_for_hotels').html(' <strong>{{@$currency}}' + total_for_hotels.toFixed(2) + '</strong>');

            grand_total  = total_for_courses + total_for_products + total_for_hotels;

            $('#grand_total').html('{{@$currency}}'+ grand_total.toFixed(2));
        }

        /*change in subtotal and total*/
        $('.courses-section .search.dropdown.no_of_persons').dropdown({
            onChange: function (selected) {
                var course_id           = $(this).data('courseid');
                var per_person_price    = course_prices[course_id];
                var number              = selected;

                var url     = '{{route('scubaya::checkout::courses::change_no_of_divers')}}';
                var _token  = '{{csrf_token()}}';

                var t       = this;

                $.post(url, {course_id: course_id, no_of_persons: number, _token: _token}, function () {
                    $(t).parent().parent().parent().parent().next().find('.subtotal').html('<h2 class="blue subtotal">{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}' + (per_person_price * number).toFixed(2) + '</h2>');

                    /*change in the total*/
                    changeTotal();
                });
            }
        });

        $('.search.dropdown.product-quantity').dropdown({
            onChange: function (selected) {
                var product_id          = $(this).data('product_id');
                var price_per_quantity  = product_prices[product_id];
                var quantity            = selected;

                var url     = '{{route('scubaya::checkout::products::change_no_of_products')}}';
                var _token  = '{{csrf_token()}}';

                var t       = this;

                $.post(url, {type:'product', product_id: product_id, quantity: quantity, _token: _token}, function () {
                    $(t).parent().parent().parent().parent().parent().next().find('.blue.subtotal').html('<h2 class="blue subtotal">{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}' + (price_per_quantity  * quantity).toFixed(2) + '</h2>');

                    /*change in the total*/
                    changeTotal();
                });
            }
        });

        $('.hotels-section .search.dropdown.no_of_persons').dropdown({
            onChange: function (selected) {
                var tariff_id   = $(this).data('tariffid');
                var price       = hotel_prices[tariff_id];
                var pppn        = $(this).data('pppn');
                var persons     = selected;

                var totalPrice  =   pppn ? (price  * persons) : price;

                var url     = '{{route('scubaya::checkout::hotel::change_no_of_persons')}}';
                var _token  = '{{csrf_token()}}';

                var t       =  this;

                $.post(url, {type:'hotel', tariff_id: tariff_id, persons: persons, _token: _token}, function () {
                    $(t).parent().parent().parent().parent().next().find('.blue.subtotal').html('<h2 class="blue subtotal">{{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}' + totalPrice.toFixed(2) + '</h2>');

                    /*change in the total*/
                    changeTotal();
                });
            }
        });

        $('#send-booking-request-form').on('submit', function () {
            if (parseInt(cookie_present)) {
                return true;
            } else {
                $(function () {
                    $('.ui.modal.scu-signin').modal('show');
                });
                return false;
            }
        });

        $('.add-to-trip').click(function (e) {
            let course_id       =   $(this).data('id');
            let no_of_persons   =   course_with_min_people[course_id];
            let url             =   '{{route('scubaya::checkout::cart::add_to_cart')}}';
            let _token          =   '{{csrf_token()}}';

            $.post(url, {type:'course', course_id: course_id, no_of_persons: no_of_persons, _token: _token}, function (data) {
                if (!data.already) {
                    $('.cart-count').show();
                    $('.cart-count').html('' + data.count + '');
                    /*reload the page*/
                    location.reload();
                }
                $.uiAlert({
                    textHead: '',
                    text: data.status,
                    bgcolor: '#48bbd1',
                    textcolor: '#fff',
                    position: 'top-right',
                    icon: 'shopping cart',
                    time: 1
                });
            });
        });
    </script>
@endsection