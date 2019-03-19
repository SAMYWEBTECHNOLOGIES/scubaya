<div class="modal fade bs-example-modal-lg" tabindex="-1" id="course-edit-request-modal{{@$course_checkout_detail->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">{{ isset($course_detail->course_name) ? ucwords($course_detail->course_name) : '' }}</h4>
                @php
                    $location       =   json_decode($course_detail->location);

                    $coursePricing  =   json_decode($course_detail->course_pricing);
                @endphp

                @if(!empty($location->address))
                    <span class="meta">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $location->address }}
                    </span>
                @endif
            </div>

            <div class="modal-body">
                <h5>Original Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Start</h5>
                        <p>
                            @if($course_detail->course_start_date)
                                {{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_start_date)->format('d/m/Y') }}
                            @else
                                ---
                            @endif
                        </p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">End </h5>
                        <p>
                            @if($course_detail->course_end_date)
                                {{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_end_date)->format('d/m/Y') }}
                            @else
                                ---
                            @endif
                        </p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">No Of Persons</h5>
                        <p>{{ $course_checkout_detail->no_of_people }}</p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">Price/Person</h5>
                        <p>
                            @if($coursePricing->price)
                                {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].$coursePricing->price }}
                            @else
                                {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].'0' }}
                            @endif
                        </p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$courseBooking->merchant_key]['symbol'].$courseBooking->total }}</strong></p>
                    </div>
                </div>

                <hr>

                <h5>New Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Start</h5>
                        <p>
                            @if($course_detail->course_start_date)
                                {{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_start_date)->format('d/m/Y') }}
                            @else
                                ---
                            @endif
                        </p>
                    </div>


                    <div class="col-md-2">
                        <h5 class="meta">End </h5>
                        <p>
                            @if($course_detail->course_end_date)
                                {{ Carbon\Carbon::createFromFormat('m-d-Y', $course_detail->course_end_date)->format('d/m/Y') }}
                            @else
                                ---
                            @endif
                        </p>
                    </div>

                    @php
                        $editData       =   json_decode($edit_booking_data->params);
                    @endphp

                    <div class="col-md-3">
                        <h5 class="meta">No Of Persons</h5>
                        <p>{{ $editData->no_of_persons }}</p>
                    </div>

                    <div class="col-md-3">
                        <h5 class="meta">Price/Person</h5>
                        <p>
                            @if($coursePricing->price)
                                {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].$coursePricing->price }}
                            @else
                                {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].'0' }}
                            @endif
                        </p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$courseBooking->merchant_key]['symbol'].($editData->no_of_persons * $coursePricing->price) }}</strong></p>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <a href="{{ route('scubaya::merchant::bookings::confirm_course_booking', [$authId, $edit_booking_data->id]) }}">
                        <button class="btn btn-success">Confirm</button>
                    </a>

                    <a href="{{ route('scubaya::merchant::bookings::decline_course_booking', [ $authId, $edit_booking_data->id]) }}">
                        <button name="decline_booking_request" class="btn btn-danger">Decline</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>