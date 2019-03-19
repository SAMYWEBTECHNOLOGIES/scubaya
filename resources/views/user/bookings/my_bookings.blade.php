@extends('user.layouts.app')
@section('title','My Bookings')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Bookings</a></li>
        <li class="active">My Bookings</li>
    </ol>
@endsection
@section('content')
    @php
        use Jenssegers\Agent\Agent as Agent;
        $agent = new Agent();

        $labelStatus    =   [
                NEW_BOOKING_REQUEST          =>  'label label-info',
                PENDING_BOOKING_REQUEST      =>  'label label-primary',
                CONFIRMED_BOOKING_REQUEST    =>  'label label-success',
                COMPLETED_BOOKING_REQUEST    =>  'label label-warning',
                CANCELLED_BOOKING_REQUEST    =>  'label label-default',
                EXPIRED_BOOKING_REQUEST      =>  'label label-danger'
        ];

        $canNotMakeEditRequest  =   false;
    @endphp

    <section class="content user-bookings-section">
       {{-- @if(session()->has('status'))
            <div class="alert alert-success">
                {!! session('status') !!}
            </div>
        @endif--}}

        <div class="row">
            <div class="col-sm-12 col-md-12">
                @if(count($bookings))
                    @foreach($bookings as $orderId  =>  $orders)
                        <div class="box box-solid">
                            <div class="box-body">
                            @foreach($orders as $merchant   =>  $items)
                                @php
                                    $merchantInfo   =   \App\Scubaya\model\User::where('id', $merchant)->first();
                                @endphp

                                <div class="row merchant-info">
                                    <div class="col-md-6">
                                        <h4 class="blue">{{ ucwords($merchantInfo->first_name).' '.ucwords($merchantInfo->last_name)}}</h4>
                                    </div>

                                    <div class="col-md-6">
                                        @if(! empty($showInvoice[$orderId][$merchant]) && $showInvoice[$orderId][$merchant])
                                        @php
                                            $invoiceId  =   \App\Scubaya\model\Invoices::where([
                                                'merchant_key'  =>  $merchant,
                                                'order_id'      =>  $orderId
                                            ])->value('id');
                                        @endphp
                                        <a href="{{ route('scubaya::user::bookings::invoices', [Auth::id(), $invoiceId]) }}" class="btn btn-primary btn-sm ad-click-event pull-right">
                                            Invoice
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                {{--<span class="meta">{{ $merchantInfo->email }}</span>--}}

                                @if(array_key_exists('courses', $items))
                                    @foreach($items['courses'] as $key =>  $value)
                                        @php
                                            /*$course_detail          =   \App\Scubaya\model\Courses::join('manage_dive_centers', 'manage_dive_centers.id', '=', 'courses.dive_center_id')
                                                                                                  ->where('courses.id', $value['course_id'])->first(['courses.*']);*/

                                            $course_detail          =   \App\Scubaya\model\Courses::where('id', $value['course_id'])->first(['courses.*']);

                                            $course_checkout_detail =   \App\Scubaya\model\CourseBookingRequest::where('cart_id', $value['cart_id'])
                                                                                                ->where('course_id', $value['course_id'])
                                                                                                ->first();

                                            /*$subAccountInfo         =   \App\Scubaya\model\WebsiteDetails::where([
                                                                                                            'website_id'    =>  $course_detail->dive_center_id,
                                                                                                            'merchant_key'  =>  $course_detail->merchant_key,
                                                                                                            'website_type'  =>  DIVE_CENTER
                                                                                                        ])->first();*/

                                            $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $course_checkout_detail->id)
                                                                                                        ->where('table_name', 'CourseBookingRequest')
                                                                                                        ->where('status', 'pending')
                                                                                                        ->first();

                                            if(! empty($editBookingData)) {
                                                $canNotMakeEditRequest =   true;

                                                /*foreach ($editBookingData as $data){
                                                    if($editBookingData->status    ==  'pending') {
                                                        $canNotMakeEditRequest =   true;
                                                    }

                                                    if($data->status    ==  CONFIRMED_EDIT_BOOKING_REQUEST) {
                                                        $count++;
                                                    }
                                                }

                                                if($count > 1) {
                                                    $canNotMakeEditRequest =   true;
                                                }*/
                                            }

                                            $course_location        =   json_decode($course_detail->location);

                                            $products               =   json_decode($course_detail->products);

                                            $coursePrice            =   json_decode($course_detail->course_pricing);
                                            $price                  =   $coursePrice->price;


                                            $productsInclInCourse   =   array();

                                            if($products) {
                                                foreach ($products as $productId    =>  $info) {
                                                    if($info->required) {
                                                        if($info->IE){
                                                            array_push($productsInclInCourse, $productId);
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="media">
                                            <div class="media-left">
                                                @if($course_detail->image)
                                                    <img src="{{asset('assets/images/scubaya/shop/courses/'.$course_detail->merchant_key.'/'.$value['course_id'].'-'.$course_detail->image)}}" alt="{{$course_detail->image}}" class="media-object">
                                                @else
                                                    <img src="{{asset('assets/images/default.png')}}" alt="Scubaya-courses" class="media-object">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <div class="clearfix">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4>{{ucwords($course_detail->course_name)}}</h4>

                                                            @if($course_location->address)
                                                                <p class="meta"><i class="fa fa-map-marker"></i> {{ $course_location->address }}</p>
                                                            @endif

                                                            @if($course_detail->course_start_date)
                                                            <div class="row padding-top-20">
                                                                <div class="col-xs-2 col-md-2 col-sm-6">
                                                                    <label>Start:</label>
                                                                </div>

                                                                <div class="col-xs-10 col-md-10 col-sm-6">
                                                                    <p>{{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_start_date)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @if($course_detail->course_end_date)
                                                            <div class="row">
                                                                <div class="col-xs-2 col-md-2 col-sm-6">
                                                                    <label>End:</label>
                                                                </div>

                                                                <div class="col-xs-6 col-md-10 col-sm-6">
                                                                    <p>{{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_end_date)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        @if($agent->isMobile())
                                                            <hr>
                                                        @endif

                                                        <div class="col-md-4 text-center course-price">
                                                            @if(($course_checkout_detail->no_of_people) > 3)
                                                                <i class="fa fa-user no-of-person"></i> x {{ $course_checkout_detail->no_of_people }}
                                                            @else
                                                                @for($i = 0; $i < $course_checkout_detail->no_of_people; $i++)
                                                                    <i class="fa fa-user no-of-person"></i>
                                                                @endfor
                                                            @endif
                                                            <h4>{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}{{number_format($price * $exchangeRate[$course_detail->merchant_key]['rate'], 2)}}</h4>
                                                            <span class="meta">Price Per Person</span>

                                                            <div class="total-item-price">
                                                                <h3>Total: <span class="blue">{{@$exchangeRate[$course_detail->merchant_key]['symbol']}}{{ number_format($course_checkout_detail->total * $exchangeRate[$course_detail->merchant_key]['rate'], 2) }}</span></h3>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 text-center">
                                                            <span class="{{$labelStatus[$course_checkout_detail->status]}} status">{{ ucwords($course_checkout_detail->status) }}</span>

                                                            <div class="margin-top-20">
                                                                <button class="btn btn-primary view-booking-btn" data-toggle="modal" data-target="#course-modal{{$value['cart_id']}}"><i class="fa fa-eye"></i></button>
                                                                <button class="btn btn-primary"
                                                                @if($course_checkout_detail->status == CANCELLED_BOOKING_REQUEST || $course_checkout_detail->status ==  EXPIRED_BOOKING_REQUEST
                                                                    || $canNotMakeEditRequest
                                                                )
                                                                    disabled
                                                                    data-toggle="tooltip"
                                                                    @if($canNotMakeEditRequest)
                                                                        title="You can not make another edit booking request because your previous request is still in pending state."
                                                                    @else
                                                                        title="You can not make another edit booking request because your request has been {{ $course_checkout_detail->status }}."
                                                                    @endif
                                                                @else
                                                                    data-toggle="modal" data-target="#edit-course-modal{{$value['cart_id']}}"
                                                                @endif
                                                                >
                                                                <i class="fa fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- open view modal for course --}}
                                        @include('user.bookings.course_modal', [
                                            'course_detail'             =>  $course_detail,
                                            'course_checkout_detail'    =>  $course_checkout_detail,
                                            'cart_id'                   =>  $value['cart_id'],
                                            'exchangeRate'              =>  isset($exchangeRate) ? $exchangeRate : null,
                                            //'subAccountInfo'            =>  $subAccountInfo
                                        ])

                                        @if($course_checkout_detail->status ==  NEW_BOOKING_REQUEST || $course_checkout_detail->status  ==  PENDING_BOOKING_REQUEST
                                            || $course_checkout_detail->status == CONFIRMED_BOOKING_REQUEST || $course_checkout_detail->status == COMPLETED_BOOKING_REQUEST
                                        )
                                            {{-- edit course request modal --}}
                                            @include('user.bookings.edit_course_booking',[
                                                'course_detail'             =>  $course_detail,
                                                'course_checkout_detail'    =>  $course_checkout_detail,
                                            ])
                                        @endif

                                        @php $canNotMakeEditRequest = false; @endphp
                                    @endforeach
                                @endif

                                @if(array_key_exists('products', $items))
                                    @foreach($items['products'] as $key =>  $value)
                                        @php
                                            $product_detail             =   \App\Scubaya\model\Products::join('shop_information', 'shop_information.id', '=', 'products.shop_id')
                                                                                                        ->where('products.id', $value['product_id'])->first();

                                            $product_checkout_detail    =   \App\Scubaya\model\ProductBookingRequest::where('cart_id', $value['cart_id'])
                                                                                                                    ->where('product_id', $value['product_id'])
                                                                                                                    ->first();

                                            /*$subAccountInfo             =   \App\Scubaya\model\WebsiteDetails::where([
                                                                                                            'website_id'    =>  $product_detail->shop_id,
                                                                                                            'merchant_key'  =>  $product_detail->merchant_key,
                                                                                                            'website_type'  =>  SHOP
                                                                                                        ])->first();*/

                                            $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $product_checkout_detail->id)
                                                                                                        ->where('table_name', 'ProductBookingRequest')
                                                                                                        ->where('status', 'pending')
                                                                                                        ->first();

                                            if(! empty($editBookingData)) {
                                                $canNotMakeEditRequest =   true;

                                                /*foreach ($editBookingData as $data){
                                                    if($editBookingData->status    ==  'pending') {
                                                        $canNotMakeEditRequest =   true;
                                                    }

                                                    if($data->status    ==  CONFIRMED_EDIT_BOOKING_REQUEST) {
                                                        $count++;
                                                    }
                                                }

                                                if($count > 1) {
                                                    $canNotMakeEditRequest =   true;
                                                }*/
                                            }

                                            if($product_detail->tax) {
                                                $price  =   $product_detail->price + ($product_detail->price * ( $product_detail->tax ) / 100);
                                            } else {
                                                $price  =   $product_detail->price;
                                            }
                                        @endphp
                                        <div class="media">
                                            <div class="media-left">
                                                <img src="{{ asset('assets/images/scubaya/shop/products/'.$product_detail->merchant_key.'/'.$value['product_id'].'-'.$product_detail->product_image) }}" alt="{{$product_detail->product_image}}" class="media-object">
                                            </div>
                                            <div class="media-body">
                                                <div class="clearfix">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4>{{ ucwords($product_detail->title) }}</h4>
                                                            <p class="meta">Manufactured By {{ ucwords($product_detail->manufacturer) }}</p>
                                                            @if($product_detail->product_type == 1)
                                                                <span class="label label-warning item-label">Rental</span>
                                                            @else
                                                                <span class="label label-info item-label">Sell</span>
                                                            @endif

                                                            <div class="row padding-top-20">
                                                                <div class="col-xs-4 col-md-3 col-sm-6">
                                                                    <label>Weight:</label>
                                                                </div>

                                                                <div class="col-xs-8 col-md-3 col-sm-6">
                                                                    <p>{{ $product_detail->weight }} Kg</p>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xs-4 col-md-3 col-sm-6">
                                                                    <label>Color:</label>
                                                                </div>

                                                                <div class="col-xs-8 col-md-3 col-sm-6">
                                                                    <span id="product_color" style="background-color: {{ $product_detail->color }}"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($agent->isMobile())
                                                            <hr>
                                                        @endif

                                                        <div class="col-md-4 text-center">
                                                            <label>Quantity: </label> <strong>{{ $product_checkout_detail->quantity }}</strong>
                                                            <h4>{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}{{number_format($price * $exchangeRate[$product_detail->merchant_key]['rate'], 2)}}</h4>
                                                            <span class="meta">Price (Tax Included)</span>

                                                            <div class="total-item-price">
                                                                <h3>Total: <span class="blue">{{@$exchangeRate[$product_detail->merchant_key]['symbol']}}{{ number_format($product_checkout_detail->total * $exchangeRate[$product_detail->merchant_key]['rate'], 2) }}</span></h3>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 text-center">
                                                            <span class="{{$labelStatus[$product_checkout_detail->status]}} status">{{ ucwords($product_checkout_detail->status) }}</span>

                                                            <div class="margin-top-20">
                                                                <button class="btn btn-primary view-booking-btn" data-toggle="modal" data-target="#product-modal{{$value['cart_id']}}"><i class="fa fa-eye"></i></button>
                                                                <button class="btn btn-primary"
                                                                @if($product_checkout_detail->status == CANCELLED_BOOKING_REQUEST || $product_checkout_detail->status ==  EXPIRED_BOOKING_REQUEST
                                                                    || $canNotMakeEditRequest
                                                                )
                                                                    disabled
                                                                    data-toggle="tooltip"

                                                                    @if($canNotMakeEditRequest)
                                                                    title="You can not make another edit booking request because your previous request is still in pending state."
                                                                    @else
                                                                    title="You can not make another edit booking request because your request has been  {{ $product_checkout_detail->status }}."
                                                                    @endif
                                                                @else
                                                                    data-toggle="modal" data-target="#edit-product-modal{{$value['cart_id']}}"
                                                                @endif
                                                                >
                                                                <i class="fa fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- open view modal for product --}}
                                        @include('user.bookings.product_modal', [
                                            'product_detail'           =>  $product_detail,
                                            'product_checkout_detail'  =>  $product_checkout_detail,
                                            'cart_id'                  =>  $value['cart_id'],
                                            'exchangeRate'             =>  isset($exchangeRate) ? $exchangeRate : null,
                                            //'subAccountInfo'           =>  $subAccountInfo
                                        ])

                                        @if($product_checkout_detail->status ==  NEW_BOOKING_REQUEST || $product_checkout_detail->status  ==  PENDING_BOOKING_REQUEST
                                            || $product_checkout_detail->status == CONFIRMED_BOOKING_REQUEST || $product_checkout_detail->status ==  COMPLETED_BOOKING_REQUEST
                                        )
                                            {{-- edit course request modal --}}
                                            @include('user.bookings.edit_product_booking',[
                                                'product_detail'             =>  $product_detail,
                                                'product_checkout_detail'    =>  $product_checkout_detail,
                                            ])
                                        @endif

                                        <?php $canNotMakeEditRequest    =   false; ?>
                                    @endforeach
                                @endif

                                @if(array_key_exists('hotels', $items))
                                    @foreach($items['hotels'] as $key =>  $value)
                                        @php
                                            $pppn                   =   false;

                                            $hotel_detail           =   \App\Scubaya\model\RoomPricing::where('room_pricing.id', '=', $value['tariff_id'])
                                                                                                        ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                                                                                        ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                                                                                        ->select('hotel.id as hotel_id', 'hotel.name', 'hotel.address', 'hotel.merchant_primary_id' ,
                                                                                                            'room_details.id', 'room_details.type','room_details.merchant_primary_id as merchant_key', 'room_details.room_image', 'room_details.features',
                                                                                                            'room_pricing.additional_tariff_data', 'room_pricing.tariff_title', 'room_pricing.tariff_description')
                                                                                                        ->first();

                                            $hotel_checkout_detail  =   \App\Scubaya\model\HotelBookingRequest::where('cart_id', $value['cart_id'])
                                                                                                              ->where('tariff_id', $value['tariff_id'])
                                                                                                              ->first();

                                            /*$subAccountInfo         =   \App\Scubaya\model\WebsiteDetails::where([
                                                                                                            'website_id'    =>  $hotel_detail->hotel_id,
                                                                                                            'merchant_key'  =>  $hotel_detail->merchant_primary_id,
                                                                                                            'website_type'  =>  HOTEL
                                                                                                        ])->first();*/

                                            $tariffData             =   (array)json_decode($hotel_detail->additional_tariff_data);

                                            if(array_key_exists('micro', $tariffData)) {
                                                $price_per_night_manually = (array)json_decode($tariffData['micro']->price_per_night_manually);
                                                $min_nights_manually      = (array)json_decode($tariffData['micro']->min_nights_manually);

                                                if(isset($hotel_checkout_detail->no_of_persons) && $tariffData['micro']->min_people <= $hotel_checkout_detail->no_of_persons) {
                                                    $maxPeople  =   $tariffData['micro']->max_people;
                                                    $minPeople  =   $tariffData['micro']->min_people;

                                                    $checkIn    =   strtotime($hotel_checkout_detail->check_in);
                                                    $checkOut   =   strtotime($hotel_checkout_detail->check_out);

                                                    $daysDifference  =   date_diff(date_create($hotel_checkout_detail->check_in), date_create($hotel_checkout_detail->check_out));
                                                    $daysDifference  =   $daysDifference->format('%a') ;
                                                    $roomPrices      =   array();
                                                    $totalPrice      =   0;

                                                    for($i = 0; $i <= $daysDifference; $i++) {

                                                        $checkin =   strtotime('+'.$i.'days', $checkIn);

                                                        foreach($price_per_night_manually as $key => $value)
                                                        {
                                                            if ($key == $checkin)
                                                                $roomPrices[$i]     =   [
                                                                    $key    =>  $value
                                                                ];
                                                        }
                                                    }

                                                    foreach($min_nights_manually as $key => $value)
                                                    {
                                                        if ($key == $checkIn)
                                                            $minNights =  $value;
                                                    }

                                                    if($minNights == ( (int)$daysDifference) || $minNights > ( (int)$daysDifference)) {
                                                        foreach ($roomPrices as $rprices) {
                                                            foreach ($rprices as $key => $value) {
                                                                if($key == $checkIn) {
                                                                    $prices     =   ( (int)$daysDifference ) * $value;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if(((int)$daysDifference ) > $minNights) {
                                                        foreach ($roomPrices as $rprices) {
                                                            foreach ($rprices as $key => $value) {
                                                                if($key ==  $checkIn) {
                                                                    $price =   $value;
                                                                }
                                                            }
                                                        }

                                                        $prices         =   $minNights * $price;
                                                        $startDate      =   strtotime('+'.$minNights.'days', $checkIn);

                                                        $remainingDays  =   ( (int)$daysDifference ) - $minNights;

                                                        for($i = 0 ; $i < $remainingDays; $i++) {
                                                            $date   =   strtotime('+'.$i.'days', $startDate);

                                                            foreach ($roomPrices as $rprices) {
                                                                foreach ($rprices as $key => $value) {
                                                                    if($key ==  $date) {
                                                                        $prices +=   $value;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if($tariffData['micro']->ignore_pppn) {
                                                        $totalPrice =   $prices;
                                                        $label      =   'Per Night';
                                                    } else {
                                                        $totalPrice =   $prices;
                                                        $pppn       =   true;
                                                        $label      =   'Per Person/Night';
                                                    }
                                                }
                                            }

                                            if(array_key_exists('advance', $tariffData)) {
                                                $maxPeople  =   $tariffData['advance']->max_people;
                                                $minPeople  =   $tariffData['advance']->min_people;

                                                $daysDifference  =   date_diff(date_create($hotel_checkout_detail->check_in), date_create($hotel_checkout_detail->check_out));
                                                $daysDifference  =   (int)($daysDifference->format('%a'));

                                                if($daysDifference >= $tariffData['advance']->min_days && $daysDifference <= $tariffData['advance']->max_days) {
                                                    if(isset($hotel_checkout_detail->no_of_persons) && $hotel_checkout_detail->no_of_persons >= $tariffData['advance']->min_people) {

                                                        $validFrom  =   strtotime(DateTime::createFromFormat('m-d-Y', $tariffData['advance']->valid_from)->format('d-m-Y'));
                                                        $validTo    =   strtotime(DateTime::createFromFormat('m-d-Y', $tariffData['advance']->valid_to)->format('d-m-Y'));

                                                        if(strtotime($hotel_checkout_detail->check_in) >= $validFrom && strtotime($hotel_checkout_detail->check_out) <= $validTo) {

                                                            if($tariffData['advance']->ignore_pppn) {
                                                                $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                                                $tariffPricingLabel['advance']  =   'Per Night';
                                                            } else {
                                                                $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                                                $pppn                           =   true;
                                                                $tariffPricingLabel['advance']  =   'Per Person/Night';
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            if(array_key_exists('normal', $tariffData)) {
                                                $maxPeople  =   $tariffData['normal']->max_people;
                                                $minPeople  =   $tariffData['normal']->min_people;

                                                /* To check global per person per night option */
                                                $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $hotel_detail->merchant_primary_id)
                                                                                                            ->first(['currency']);
                                                $pricingSetting  =   json_decode($pricingSetting->currency);

                                                /* If Price per person per night option is set to yes globally
                                                 * then include person and night in price calculation
                                                 * else include nights only.
                                                 */
                                                if(isset($hotel_checkout_detail->no_of_persons) && $tariffData['normal']->min_people <= $hotel_checkout_detail->no_of_persons) {
                                                    $daysDifference  =   date_diff(date_create($hotel_checkout_detail->check_in), date_create($hotel_checkout_detail->check_out));
                                                    $daysDifference  =   (int)($daysDifference->format('%a'));

                                                    if($pricingSetting->prices_pppn) {
                                                        $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                                        $pppn        =   true;
                                                        $label       =   'Per Person/Night';
                                                    } else {
                                                        $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                                        $label       =   'Per Night';
                                                    }
                                                }
                                            }

                                            $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $hotel_checkout_detail->id)
                                                                                                        ->where('table_name', 'HotelBookingRequest')
                                                                                                        ->where('status', 'pending')
                                                                                                        ->first();

                                            if(! empty($editBookingData)) {
                                                $canNotMakeEditRequest =   true;
                                                $count                 =   0;

                                                /*foreach ($editBookingData as $data){
                                                    if($editBookingData->status    ==  'pending') {
                                                        $canNotMakeEditRequest =   true;
                                                    }

                                                    if($data->status    ==  CONFIRMED_EDIT_BOOKING_REQUEST) {
                                                        $count++;
                                                    }
                                                }

                                                if($count > 1) {
                                                    $canNotMakeEditRequest =   true;
                                                }*/
                                            }
                                        @endphp
                                        <div class="media">
                                            <div class="media-left">
                                                <img src="{{asset('assets/images/scubaya/rooms/'.$hotel_detail->id.'-'.$hotel_detail->room_image)}}" alt="{{$hotel_detail->room_image}}" class="media-object">
                                            </div>
                                            <div class="media-body">
                                                <div class="clearfix">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4>{{ucwords($hotel_detail->name)}} </h4>
                                                            <p class="meta">{{ ucwords($hotel_detail->tariff_title) }}</p>
                                                            <span class="label label-danger item-label">{{ $hotel_detail->type }}</span>

                                                            <div class="row padding-top-20">
                                                                <div class="col-xs-4 col-sm-6 col-md-4">
                                                                    <label>Check In:</label>
                                                                </div>

                                                                <div class="col-xs-4 col-sm-6 col-md-8">
                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_in)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xs-4 col-sm-6 col-md-4">
                                                                    <label>Check Out:</label>
                                                                </div>

                                                                <div class="col-xs-4 col-sm-6 col-md-4">
                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_out)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($agent->isMobile())
                                                            <hr>
                                                        @endif

                                                        <div class="col-md-4 text-center">
                                                            @if(($hotel_checkout_detail->no_of_persons) > 3)
                                                                <i class="fa fa-user no-of-person"></i> x {{ $hotel_checkout_detail->no_of_persons }}
                                                            @else
                                                                @for($i = 0; $i < $hotel_checkout_detail->no_of_persons; $i++)
                                                                    <i class="fa fa-user no-of-person"></i>
                                                                @endfor
                                                            @endif
                                                            <h4>{{@$exchangeRate[$hotel_detail->merchant_key]['symbol']}}{{number_format($totalPrice * $exchangeRate[$hotel_detail->merchant_key]['rate'], 2)}}</h4>
                                                            <span class="meta">{{ $label }}</span>

                                                            @php
                                                            if($pppn) {
                                                                $netAmount  =   $totalPrice * $exchangeRate[$hotel_detail->merchant_key]['rate'] * $hotel_checkout_detail->no_of_persons;
                                                            } else {
                                                                $netAmount  =   $totalPrice * $exchangeRate[$hotel_detail->merchant_key]['rate'];
                                                            }
                                                            @endphp

                                                            <div class="total-item-price">
                                                                <h3>Total: <span class="blue">{{@$exchangeRate[$hotel_detail->merchant_key]['symbol']}}{{ number_format($netAmount, 2) }}</span></h3>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 text-center">
                                                            <span class="{{$labelStatus[$hotel_checkout_detail->status]}} status">{{ ucwords($hotel_checkout_detail->status) }}</span>

                                                            <div class="margin-top-20">
                                                                <button class="btn btn-primary view-booking-btn" data-toggle="modal" data-target="#hotel-modal{{$hotel_checkout_detail->cart_id}}"><i class="fa fa-eye"></i></button>
                                                                <button class="btn btn-primary"
                                                                @if($hotel_checkout_detail->status == CANCELLED_BOOKING_REQUEST || $hotel_checkout_detail->status ==  EXPIRED_BOOKING_REQUEST
                                                                    || $canNotMakeEditRequest
                                                                )
                                                                    disabled
                                                                    data-toggle="tooltip"

                                                                    @if($canNotMakeEditRequest)
                                                                    title="You can not make another edit booking request because your previous request is still in pending state."
                                                                    @else
                                                                    title="You can not make another edit booking request because your request has been {{ $hotel_checkout_detail->status }}."
                                                                    @endif
                                                                @else
                                                                    data-toggle="modal" data-target="#edit-hotel-modal{{$hotel_checkout_detail->cart_id}}"
                                                                @endif
                                                                >
                                                                <i class="fa fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- open view modal for hotel --}}
                                        @include('user.bookings.hotel_modal', [
                                            'hotel_detail'           =>  $hotel_detail,
                                            'hotel_checkout_detail'  =>  $hotel_checkout_detail,
                                            'label'                  =>  $label,
                                            'total_price'            =>  $totalPrice,
                                            'pppn'                   =>  $pppn,
                                            'exchangeRate'           =>  isset($exchangeRate) ? $exchangeRate : null,
                                            //'subAccountInfo'         =>  $subAccountInfo
                                        ])

                                        @if($hotel_checkout_detail->status ==  NEW_BOOKING_REQUEST || $hotel_checkout_detail->status  ==  PENDING_BOOKING_REQUEST
                                            || $hotel_checkout_detail->status == CONFIRMED_BOOKING_REQUEST || $hotel_checkout_detail->status ==  COMPLETED_BOOKING_REQUEST
                                        )
                                            {{-- edit course request modal --}}
                                            @include('user.bookings.edit_hotel_booking',[
                                                'hotel_detail'             =>  $hotel_detail,
                                                'hotel_checkout_detail'    =>  $hotel_checkout_detail,
                                                'max_people'               =>  $maxPeople,
                                                'min_people'               =>  $minPeople
                                            ])
                                        @endif

                                        <?php $canNotMakeEditRequest    =   false; ?>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="media">
                                <p class="text-center padding-20">No Purchases Found Yet!!</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        @if(session()->has('status'))
            var message =   '{{ session('status') }}';
            $.notify({
                message:message
            },{
                type: 'pastel-warning',
                delay: 5000,
                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<span data-notify="title">{1}</span>' +
                '<span data-notify="message">{2}</span>' +
                '</div>'
            });
        @endif
    </script>
@endsection