<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            @if(count($hotelBookings))
            <thead>
                <tr>
                    <th>#</th>
                    <th width="15%">Hotel Name</th>
                    <th width="15%">Room Tariff</th>
                    <th width="10%">Room Type</th>
                    <th width="5%">Check In</th>
                    <th width="10%">Check Out</th>
                    <th width="5%">No Of Persons</th>
                    <th width="8%">Price</th>
                    <th width="10%">Total</th>
                    <th width="12%">Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($hotelBookings as $hotelBooking)
                    @php
                        $pppn               =   false;
                        $totalPrice         =   0;
                        $minPeople          =   0;
                        $maxPeople          =   0;

                        $hotelDetail        =   \App\Scubaya\model\RoomPricing::where('room_pricing.id', '=', $hotelBooking->tariff_id)
                                                                                ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                                                                ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                                                                ->select('hotel.name', 'hotel.address', 'hotel.merchant_primary_id' ,
                                                                                'room_details.type',  'room_details.features',
                                                                                'room_pricing.additional_tariff_data', 'room_pricing.tariff_title', 'room_pricing.tariff_description')->first();

                        $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $hotelBooking->id)
                                                                                ->where([
                                                                                'table_name' => 'HotelBookingRequest',
                                                                                'status'     => 'pending'
                                                                                ])
                                                                                ->first();

                        $userInfo           =   \App\Scubaya\model\Cart::where('cart.id', $hotelBooking->cart_id)
                                                                    ->join('users', 'users.id', '=', 'cart.user_key')
                                                                    ->first(['first_name', 'last_name', 'email']);

                        if($editBookingData) {
                            $noOfPersons        =   (json_decode($editBookingData->params)->no_of_persons);
                        }

                        $tariffData             =   (array)json_decode($hotelDetail->additional_tariff_data);

                        if(array_key_exists('micro', $tariffData)) {
                            $price_per_night_manually = (array)json_decode($tariffData['micro']->price_per_night_manually);
                            $min_nights_manually      = (array)json_decode($tariffData['micro']->min_nights_manually);

                            $maxPeople  =   $tariffData['micro']->max_people;
                            $minPeople  =   $tariffData['micro']->min_people;

                            /*if(isset($noOfPersons) && $tariffData['micro']->min_people <= $noOfPersons) {*/
                                $checkIn    =   strtotime($hotelBooking->check_in);
                                $checkOut   =   strtotime($hotelBooking->check_out);

                                $daysDifference  =   date_diff(date_create($hotelBooking->check_in), date_create($hotelBooking->check_out));
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
                            /*}  else {
                                // calculate merchant request time
                                $today  = date('Y-m-d', $_SERVER['REQUEST_TIME']);
                                $today  = explode('-', $today);

                                /* mktime(hour, minute, second, month, day, year) */
                                /*$epoch  = mktime(0, 0, 0, $today[1], $today[2], $today[0]);

                                foreach($price_per_night_manually as $key => $value)
                                {
                                    if ($key == $epoch)
                                        $totalPrice =  $value;
                                }

                                if($tariffData['micro']->ignore_pppn) {
                                    $label      =   'Per Night';
                                } else {
                                    $pppn       =   true;
                                    $label      =   'Per Person/Night';
                                }
                            }*/
                        }

                        if(array_key_exists('advance', $tariffData)) {
                            $daysDifference  =   date_diff(date_create($hotelBooking->check_in), date_create($hotelBooking->check_out));
                            $daysDifference  =   (int)($daysDifference->format('%a'));

                            /*if($daysDifference >= $tariffData['advance']->min_days && $daysDifference <= $tariffData['advance']->max_days) {
                                if(isset($noOfPersons) && $noOfPersons >= $tariffData['advance']->min_people) {*/

                                    $maxPeople  =   $tariffData['advance']->max_people;
                                    $minPeople  =   $tariffData['advance']->min_people;

                                    /*$validFrom  =   strtotime(DateTime::createFromFormat('m-d-Y', $tariffData['advance']->valid_from)->format('d-m-Y'));
                                    $validTo    =   strtotime(DateTime::createFromFormat('m-d-Y', $tariffData['advance']->valid_to)->format('d-m-Y'));

                                    if(strtotime($hotelBooking->check_in) >= $validFrom && strtotime($hotelBooking->check_out) <= $validTo) {*/

                                        if($tariffData['advance']->ignore_pppn) {
                                            $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                            $tariffPricingLabel['advance']  =   'Per Night';
                                        } else {
                                            $totalPrice                     =   $tariffData['advance']->rate * $daysDifference;
                                            $pppn                           =   true;
                                            $tariffPricingLabel['advance']  =   'Per Person/Night';
                                        }
                                   /* }
                                }*/ /*else {
                                    if($tariffData['advance']->ignore_pppn) {
                                        $tariffPricingLabel['advance']  =   'Per Night';
                                    } else {
                                        $pppn                           =   true;
                                        $tariffPricingLabel['advance']  =   'Per Person/Night';
                                    }
                                }*/
                           /* }*/
                        }

                        if(array_key_exists('normal', $tariffData)) {
                            /* To check global per person per night option */
                            $pricingSetting  =   \App\Scubaya\model\RoomPricingSettings::where('merchant_primary_id', $hotelDetail->merchant_primary_id)
                                                                                        ->first(['currency']);
                            $pricingSetting  =   json_decode($pricingSetting->currency);

                            /* If Price per person per night option is set to yes globally
                             * then include person and night in price calculation
                             * else include nights only.
                             */
                            /*if(isset($noOfPersons) && $tariffData['normal']->min_people <= $noOfPersons) {*/
                                $maxPeople       =   $tariffData['normal']->max_people;
                                $minPeople       =   $tariffData['normal']->min_people;

                                $daysDifference  =   date_diff(date_create($hotelBooking->check_in), date_create($hotelBooking->check_out));
                                $daysDifference  =   (int)($daysDifference->format('%a'));

                                if($pricingSetting->prices_pppn) {
                                    $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                    $pppn        =   true;
                                    $label       =   'Per Person/Night';
                                } else {
                                    $totalPrice  =   $tariffData['normal']->rate * $daysDifference;
                                    $label       =   'Per Night';
                                }
                            /*} *//*else {
                                if($pricingSetting->prices_pppn) {
                                    $pppn        =   true;
                                    $label       =   'Per Person/Night';
                                } else {
                                    $label       =   'Per Night';
                                }
                            }*/
                        }

                    @endphp
                    <tr>
                        <td>{{ $sno++ }}</td>
                        <td>{{ ucwords($hotelDetail->name) }}</td>
                        <td>{{ ucwords($hotelDetail->tariff_title) }}</td>
                        <td>{{ ucwords($hotelDetail->type) }}</td>
                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotelBooking->check_in)->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotelBooking->check_out)->format('d/m/Y') }}</td>
                        <td>{{ $hotelBooking->no_of_persons }}</td>
                        <td>{{ $exchangeRate[$hotelDetail->merchant_primary_id]['symbol'].$totalPrice }}<br><span class="meta">{{ $label }}</span></td>
                        <td>
                            <span class="blue"><strong>{{ $exchangeRate[$hotelDetail->merchant_primary_id]['symbol'].$hotelBooking->total }}</strong></span>
                            <br>
                            <span class="meta">{{ $label }}</span>
                        </td>
                        <td>
                            <select name="hotel_booking_status" class="form-control hotel_booking_status" id="{{ (new \Hashids\Hashids())->encode($hotelBooking->cart_id) }}">
                                <option value="new" @if($hotelBooking->status == 'new') selected @endif>New</option>
                                <option value="pending" @if($hotelBooking->status == 'pending') selected @endif>Pending</option>
                                <option value="cancelled" @if($hotelBooking->status == 'cancelled') selected @endif>Cancelled</option>
                                <option value="completed" @if($hotelBooking->status == 'completed') selected @endif>Completed</option>
                                <option value="confirmed" @if($hotelBooking->status == 'confirmed') selected @endif>Confirmed</option>
                                <option value="expired" @if($hotelBooking->status == 'expired') selected @endif>Expired</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class=" btn btn-success" data-toggle="modal" data-target="#hotel-modal{{ $hotelBooking->id }}">
                                <i class="fa fa-eye"></i>
                            </button>

                            <button type="button" class="button-blue btn btn-primary" data-toggle="modal" data-target="#edit-hotel-modal{{ $hotelBooking->id }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </td>
                        @if(! empty($editBookingData) && $editBookingData->status == 'pending')
                            <td>
                                <button type="button" class="btn-edit-request btn btn-primary" data-toggle="modal" data-target="#hotel-edit-request-modal{{$hotelBooking->id}}">
                                    Edit Request
                                </button>
                            </td>
                        @else
                            <td></td>
                        @endif
                    </tr>

                    {{-- hotel view modal --}}
                    @include('merchant.bookings.item_modals.hotel_modal', [
                        'hotel_detail'             =>  $hotelDetail,
                        'hotel_checkout_detail'    =>  $hotelBooking,
                        'price'                    =>  $totalPrice,
                        'label'                    =>  $label,
                        'user_info'                =>  $userInfo
                    ])

                    {{-- include hotel edit booking request modal if there is any edit booking request for it --}}
                    @if($editBookingData)
                        @include('merchant.bookings.item_edit_request_modals.hotels_edit_request', [
                            'hotel_detail'             =>  $hotelDetail,
                            'hotel_checkout_detail'    =>  $hotelBooking,
                            'edit_booking_data'        =>  $editBookingData,
                            'label'                    =>  $label,
                            'price'                    =>  $totalPrice,
                            'pppn'                     =>  $pppn
                        ])
                    @endif

                    {{-- hotel edit modal --}}
                    @include('merchant.bookings.item_edit_modals.hotel_edit', [
                        'hotel_detail'             =>  $hotelDetail,
                        'hotel_checkout_detail'    =>  $hotelBooking,
                        'min_people'               =>  $minPeople,
                        'max_people'               =>  $maxPeople,
                        'user_info'                =>  $userInfo
                    ])
                @endforeach
            </tbody>
            @else
                <tr>
                    <th class="text-center">No Hotel Room Booking Found!</th>
                </tr>
            @endif
        </table>
    </div>
</div>