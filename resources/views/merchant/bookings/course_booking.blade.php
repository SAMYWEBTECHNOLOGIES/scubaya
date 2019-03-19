<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            @if(count($courseBookings))
            <thead>
                <tr>
                    <th>#</th>
                    <th width="20%">Name</th>
                    <th width="20%">Location</th>
                    <th width="5%">Start Date</th>
                    <th width="5%">End Date</th>
                    <th width="10%">No Of Persons</th>
                    <th width="5%">Price/Person</th>
                    <th width="5%">Total</th>
                    <th width="15%">Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($courseBookings as $courseBooking)
                @php
                    $courseDetail       =   \App\Scubaya\model\Courses::where('id', $courseBooking->course_id)->first();

                    $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $courseBooking->id)
                                                                        ->where([
                                                                        'table_name'    =>  'CourseBookingRequest',
                                                                        'status'        =>  'pending'
                                                                        ])
                                                                        ->first();

                    $userInfo           =   \App\Scubaya\model\Cart::where('cart.id', $courseBooking->cart_id)
                                                                    ->join('users', 'users.id', '=', 'cart.user_key')
                                                                    ->first(['first_name', 'last_name', 'email']);
                @endphp
                <tr>
                    <td>{{ $sno++ }}</td>
                    <td>{{ ucwords($courseDetail->course_name) }}</td>

                    @php
                        $location   =   json_decode($courseDetail->location);
                    @endphp
                    <td>{{ $location->address or '---'}}</td>
                    <td>
                        @if($courseDetail->course_start_date)
                            {{ Carbon\Carbon::createFromFormat('m-d-Y', $courseDetail->course_start_date)->format('d/m/Y') }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if($courseDetail->course_end_date)
                            {{ Carbon\Carbon::createFromFormat('m-d-Y', $courseDetail->course_end_date)->format('d/m/Y') }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ $courseBooking->no_of_people }}</td>

                    @php
                        $coursePricing  =   json_decode($courseDetail->course_pricing);
                    @endphp
                    <td>
                        @if($coursePricing->price)
                            {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].$coursePricing->price }}
                        @else
                            {{ $exchangeRate[$courseBooking->merchant_key]['symbol'].'0' }}
                        @endif
                    </td>
                    <td class="blue"><strong>{{ $exchangeRate[$courseBooking->merchant_key]['symbol'].$courseBooking->total }}</strong></td>
                    <td>
                        <select name="course_booking_status" class="form-control course_booking_status" id="{{ (new \Hashids\Hashids())->encode($courseBooking->cart_id) }}">
                            <option value="new" @if($courseBooking->status == 'new') selected @endif>New</option>
                            <option value="pending" @if($courseBooking->status == 'pending') selected @endif>Pending</option>
                            <option value="cancelled" @if($courseBooking->status == 'cancelled') selected @endif>Cancelled</option>
                            <option value="completed" @if($courseBooking->status == 'completed') selected @endif>Completed</option>
                            <option value="confirmed" @if($courseBooking->status == 'confirmed') selected @endif>Confirmed</option>
                            <option value="expired" @if($courseBooking->status == 'expired') selected @endif>Expired</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class=" btn btn-success" data-toggle="modal" data-target="#course-modal{{$courseBooking->id}}">
                            <i class="fa fa-eye"></i>
                        </button>

                        <button type="button" class="button-blue btn btn-primary" data-toggle="modal" data-target="#edit-course-modal{{$courseBooking->id}}">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </td>
                    @if(! empty($editBookingData) && $editBookingData->status == 'pending')
                        <td>
                            <button type="button" class="btn-edit-request btn btn-primary" data-toggle="modal" data-target="#course-edit-request-modal{{$courseBooking->id}}">
                                Edit Request
                            </button>
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>

                {{-- course view modal --}}
                @include('merchant.bookings.item_modals.course_modal', [
                    'course_detail'             =>  $courseDetail,
                    'course_checkout_detail'    =>  $courseBooking,
                    'user_info'                 =>  $userInfo
                ])

                {{-- include course edit booking request modal if there is any edit booking request for it --}}
                @if($editBookingData)
                    @include('merchant.bookings.item_edit_request_modals.course_edit_request', [
                        'course_detail'             =>  $courseDetail,
                        'course_checkout_detail'    =>  $courseBooking,
                        'edit_booking_data'         =>  $editBookingData
                    ])
                @endif

                {{-- course edit modal --}}
                @include('merchant.bookings.item_edit_modals.course_edit', [
                    'course_detail'             =>  $courseDetail,
                    'course_checkout_detail'    =>  $courseBooking,
                    'user_info'                 =>  $userInfo
                ])
                @endforeach
            </tbody>
            @else
                <tr>
                    <th class="text-center">No Course Booking Found!</th>
                </tr>
            @endif
        </table>
    </div>
</div>