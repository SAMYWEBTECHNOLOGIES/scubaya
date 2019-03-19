<div class="modal fade bs-example-modal-lg" tabindex="-1" id="hotel-edit-request-modal{{@$hotel_checkout_detail->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">{{ isset($hotel_detail->name) ? ucwords($hotel_detail->name) : '' }}</h4>

                @if(!empty($hotel_detail->address))
                    <span class="meta">
                          <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $hotel_detail->address }}
                    </span>
                @endif
            </div>

            <div class="modal-body">
                <h5>Original Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Check In</h5>
                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_in)->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Check Out </h5>
                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_out)->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">No Of Persons</h5>
                        <p>{{ $hotel_checkout_detail->no_of_persons }}</p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">{{ 'Price '.$label }}</h5>
                        <p>{{ $exchangeRate[$hotel_checkout_detail->merchant_key]['symbol'].$price }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$hotel_checkout_detail->merchant_key]['symbol'].$hotel_checkout_detail->total }}</strong></p>
                    </div>
                </div>

                <hr>

                <h5>New Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Check In</h5>
                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_in)->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Check Out </h5>
                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $hotel_checkout_detail->check_out)->format('d/m/Y') }}</p>
                    </div>

                    @php
                        $editData       =   json_decode($edit_booking_data->params);
                    @endphp

                    <div class="col-md-3">
                        <h5 class="meta">No Of Persons</h5>
                        <p>{{ $editData->no_of_persons }}</p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">{{ 'Price '.$label }}</h5>
                        <p>{{ $exchangeRate[$hotel_checkout_detail->merchant_key]['symbol'].$price }}</p>
                    </div>

                    @php
                        if($pppn){
                            $totalPrice =  $editData->no_of_persons *   $price;
                        } else {
                            $totalPrice =  $price;
                        }
                    @endphp

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$hotel_checkout_detail->merchant_key]['symbol'].$totalPrice }}</strong></p>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <a href="{{ route('scubaya::merchant::bookings::confirm_hotel_booking', [$authId, $edit_booking_data->id, $totalPrice]) }}">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </a>

                    <a href="{{ route('scubaya::merchant::bookings::decline_hotel_booking', [$authId, $edit_booking_data->id]) }}">
                        <button name="decline_booking_request" class="btn btn-danger">Decline</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>