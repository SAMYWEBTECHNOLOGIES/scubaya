@extends('user.layouts.app')
@section('title','My Bookings')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Bookings</a></li>
        <li class="active">Invoices</li>
    </ol>
@endsection
@section('content')
    @php
        $user       =   Auth::user();
        $currency   =   null;
    @endphp
    <div class="content">
        <div class="invoice">
            <div class="invoice-container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-company">
                                    <img style="width: 50%" src="{{asset('assets/images/logo/Scubaya-text-logo-original-color.png')}}" alt="scubaya.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-number text-right">
                                    <h3>Invoice #{{ $invoice->invoice_no }}</h3>

                                    @php
                                        $orderId    =   \App\Scubaya\model\Orders::where('id', $invoice->order_id)->value('order_id');
                                    @endphp

                                    <p>Order #{{ $orderId }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-container invoice-container-highlight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-address">
                                    <h5>Billing From</h5>
                                    <p>
                                        Scubaya.com<br>
                                        795 Netherlands<br>
                                        Amsterdam, Netherlands<br>
                                        Phone: (804) 123-5432<br>
                                    </p>
                                </div>
                            </div>
                            @php
                                $userInfo    =   \App\Scubaya\model\UserPersonalInformation::where('user_key',\Illuminate\Support\Facades\Auth::id())
                                                    ->first(['street', 'postal_code', 'city', 'country', 'phone']);

                                if($userInfo) {
                                    $street       =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($userInfo->street, 'street');
                                    $pcode        =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($userInfo->postal_code, 'pcode');
                                    $city         =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($userInfo->city, 'city');
                                    $country      =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($userInfo->country, 'country');
                                    $phone        =   \App\Scubaya\model\UserPersonalInformation::formatDataToShow($userInfo->phone, 'phone');
                                }
                            @endphp

                            <div class="col-md-6">
                                <div class="invoice-address text-right">
                                    <h5>Billing To</h5>
                                    <p>
                                        {{ ucwords(decrypt($user->first_name)).' '.ucwords(decrypt($user->last_name)) }}
                                        <br>
                                        @if(!empty($street->street)){{ $street->street }} @endif
                                        <br>
                                        @if(!empty($pcode->pcode)) {{ $pcode->pcode. ', '}} @endif
                                        @if(!empty($city->city)) {{ $city->city. ', '}} @endif
                                        @if(!empty($country->country)) {{ $country->country }} @endif
                                        <br>
                                        @if($phone->phone)
                                            Phone:{{$phone->phone}} <br>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $bookeditems        =   (array)json_decode($invoice->booking_id);

                $bookedCourses      =   isset($bookeditems['course']) ? $bookeditems['course'] : null ;
                $bookedProducts     =   isset($bookeditems['product']) ? $bookeditems['product'] : null;
                $bookedHotels       =   isset($bookeditems['hotel']) ? $bookeditems['hotel'] : null;

                $total              =   0;
            @endphp

            <div class="invoice-container">
                <div class="row">
                    <div class="col-lg-12">
                        @if($bookedCourses)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2" width="150">Course</th>
                                        <th width="120">Start</th>
                                        <th width="120">End</th>
                                        <th width="120">No Of Persons</th>
                                        <th width="120">Price/Person</th>
                                        <th width="120">Total</th>
                                    </tr>
                                </thead>

                                <tbody class="text-thin">
                                    @php $sno = 1; @endphp
                                    @foreach($bookedCourses as $bookedCourse)
                                        @php
                                            $courseBookingInfo  =   \App\Scubaya\model\CourseBookingRequest::where('id',$bookedCourse)->first();
                                            $total                  +=  $courseBookingInfo->total;

                                            $courseInfo         =   \App\Scubaya\model\Courses::where('id', $courseBookingInfo->course_id)->first();

                                            $exchangeRate       =   (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$courseInfo->merchant_key))->getExchangeRate();
                                            $currency            =   $exchangeRate[$courseInfo->merchant_key]['symbol'];
                                        @endphp
                                        <tr>
                                            <td>{{ $sno++ }}</td>
                                            <td>{{ ucwords($courseInfo->course_name) }}</td>
                                            <td>{{ Carbon\Carbon::createFromFormat('m-d-Y', $courseInfo->course_start_date)->format('d/m/Y') }}</td>
                                            <td>{{ Carbon\Carbon::createFromFormat('m-d-Y', $courseInfo->course_end_date)->format('d/m/Y') }}</td>
                                            <td>{{$courseBookingInfo->no_of_people}}</td>

                                            @php
                                                $coursePricing  =   json_decode($courseInfo->course_pricing);
                                            @endphp

                                            <td>{{@$exchangeRate[$courseInfo->merchant_key]['symbol'].$coursePricing->price }}</td>
                                            <td>{{@$exchangeRate[$courseInfo->merchant_key]['symbol'].$courseBookingInfo->total * $exchangeRate[$courseInfo->merchant_key]['rate'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if($bookedProducts)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="2" width="150">Product</th>
                                    <th width="120">Weight</th>
                                    <th width="120">Quantity</th>
                                    <th width="120">Tax</th>
                                    <th width="120">Price</th>
                                    <th width="120">Total</th>
                                </tr>
                                </thead>

                                <tbody class="text-thin">
                                    @php $sno = 1; @endphp
                                    @foreach($bookedProducts as $bookedProduct)
                                        @php
                                            $productBookingInfo     =   \App\Scubaya\model\ProductBookingRequest::where('id', $bookedProduct)->first();
                                            $total                  +=  $productBookingInfo->total;

                                            $productInfo            =   \App\Scubaya\model\Products::where('id', $productBookingInfo->product_id)->first();

                                            $exchangeRate           =   (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$productInfo->merchant_key))->getExchangeRate();
                                            $currency               =   $exchangeRate[$productInfo->merchant_key]['symbol'];
                                        @endphp
                                        <tr>
                                            <td>{{ $sno++ }}</td>
                                            <td>{{ ucwords($productInfo->title) }}</td>
                                            <td>{{ $productInfo->weight }}</td>
                                            <td>{{ $productBookingInfo->quantity }}</td>
                                            <td>{{ $productInfo->tax or 0 }}%</td>
                                            <td>{{ $exchangeRate[$productInfo->merchant_key]['symbol'].$productInfo->price }}</td>
                                            <td>{{@$exchangeRate[$productInfo->merchant_key]['symbol'].$productBookingInfo->total * $exchangeRate[$productInfo->merchant_key]['rate']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if($bookedHotels)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="2" width="150">Room</th>
                                    <th width="120">Check In</th>
                                    <th width="120">Check Out</th>
                                    <th width="120">No Of Persons</th>
                                    <th width="120">Price</th>
                                    <th width="120">Total</th>
                                </tr>
                                </thead>

                                <tbody class="text-thin">
                                    @php $sno = 1;$label = ''; @endphp
                                    @foreach($bookedHotels as $bookedHotel)
                                        @php
                                            $roomBookingInfo     =   \App\Scubaya\model\HotelBookingRequest::where('id', $bookedHotel)->first();
                                            $total               +=  $roomBookingInfo->total;

                                            $roomInfo            =   \App\Scubaya\model\RoomPricing::join('room_details', 'room_details.id', '=', 'room_pricing.room_id')
                                                                                                ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                                                                                ->select('hotel.name', 'hotel.address', 'hotel.merchant_primary_id' ,'room_details.type',  'room_details.features',
                                                                                                        'room_pricing.additional_tariff_data', 'room_pricing.tariff_title', 'room_pricing.tariff_description')
                                                                                                ->where('room_pricing.id', $roomBookingInfo->tariff_id)
                                                                                                ->first();

                                            $tariffData          =   (array)json_decode($roomInfo->additional_tariff_data);

                                            if(array_key_exists('micro', $tariffData)) {
                                                $price_per_night_manually = (array)json_decode($tariffData['micro']->price_per_night_manually);
                                                $min_nights_manually      = (array)json_decode($tariffData['micro']->min_nights_manually);

                                                $maxPeople  =   $tariffData['micro']->max_people;
                                                $minPeople  =   $tariffData['micro']->min_people;

                                                $checkIn    =   strtotime($roomBookingInfo->check_in);
                                                $checkOut   =   strtotime($roomBookingInfo->check_out);

                                                $daysDifference  =   date_diff(date_create($roomBookingInfo->check_in), date_create($roomBookingInfo->check_out));
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
                                                    $label      =   'Person/Night';
                                                }
                                            }

                                            if(array_key_exists('advance', $tariffData)) {
                                                $daysDifference  =   date_diff(date_create($roomBookingInfo->check_in), date_create($roomBookingInfo->check_out));
                                                $daysDifference  =   (int)($daysDifference->format('%a'));

                                                $maxPeople  =   $tariffData['advance']->max_people;
                                                $minPeople  =   $tariffData['advance']->min_people;

                                                if($tariffData['advance']->ignore_pppn) {
                                                    $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                                    $tariffPricingLabel['advance']  =   'Per Night';
                                                } else {
                                                    $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                                    $pppn                           =   true;
                                                    $tariffPricingLabel['advance']  =   'Person/Night';
                                                }
                                            }

                                            if(array_key_exists('normal', $tariffData)) {
                                                /* To check global per person per night option */
                                                $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $roomInfo->merchant_primary_id)
                                                                                                            ->first(['currency']);
                                                $pricingSetting  =   json_decode($pricingSetting->currency);

                                                /* If Price per person per night option is set to yes globally
                                                 * then include person and night in price calculation
                                                 * else include nights only.
                                                 */
                                                $maxPeople       =   $tariffData['normal']->max_people;
                                                $minPeople       =   $tariffData['normal']->min_people;

                                                $daysDifference  =   date_diff(date_create($roomBookingInfo->check_in), date_create($roomBookingInfo->check_out));
                                                $daysDifference  =   (int)($daysDifference->format('%a'));

                                                if($pricingSetting->prices_pppn) {
                                                    $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                                    $pppn        =   true;
                                                    $label       =   'Person/Night';
                                                } else {
                                                    $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                                    $label       =   'Per Night';
                                                }
                                            }

                                            $exchangeRate        =   (new \App\Scubaya\Helpers\ExchangeRateHelper($ip, (array)$roomInfo->merchant_primary_id))->getExchangeRate();
                                            $currency             =   $exchangeRate[$roomInfo->merchant_primary_id]['symbol'];
                                        @endphp
                                        <tr>
                                            <td>{{ $sno++ }}</td>
                                            <td>{{ ucwords($roomInfo->tariff_title)}}<br><b>{{ ucwords($roomInfo->name) }}</b></td>
                                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $roomBookingInfo->check_in)->format('d/m/Y') }}</td>
                                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $roomBookingInfo->check_out)->format('d/m/Y') }}</td>
                                            <td>{{ $roomBookingInfo->no_of_persons }}</td>
                                            <td>{{ $exchangeRate[$roomInfo->merchant_primary_id]['symbol'].$totalPrice }}<br><span class="meta">{{ $label }}</span></td>
                                            <td>
                                                {{ @$exchangeRate[$roomInfo->merchant_primary_id]['symbol'].$roomBookingInfo->total * $exchangeRate[$roomInfo->merchant_primary_id]['rate'] }}
                                                <br>
                                                <span class="meta">{{ $label }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 col-md-offset-8">
                                <table class="table table-bordered invoice-table-total">
                                    <tbody>
                                    <tr class="invoice-table-highlight">
                                        <td class="text-bold">Total Amount:</td>
                                        <td width="150">{{ $currency.$total }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-lg-12">
                        <form method="post" target="_blank" action="{{route('scubaya::user::bookings::invoices',[Auth::id(),$invoice->id])}}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Generate PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection