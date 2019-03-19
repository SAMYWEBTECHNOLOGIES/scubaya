@php
    $merchantId =   (new \Hashids\Hashids())->encode($course_detail->merchant_key);
    $bookingId  =   (new \Hashids\Hashids())->encode($course_checkout_detail->id);
@endphp

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="edit-course-modal{{@$course_checkout_detail->cart_id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">Edit {{ isset($course_detail->course_name) ? ucwords($course_detail->course_name) : '' }}</h4>
            </div>

            <div class="modal-body">
                <form name="edit_course_booking" method="post" action="{{ route('scubaya::user::bookings::edit_booking', [Auth::id(), $merchantId, $bookingId]) }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input name="item_type" type="hidden" value="course">
                    </div>

                    <div class="form-group">
                        @php
                            $coursePricing = json_decode($course_detail->course_pricing);
                        @endphp
                        <label for="no_of_persons">No Of Persons:</label>
                        <select class="form-control" name="no_of_persons">
                            @for($i = $coursePricing->min_people; $i <= $coursePricing->max_people; $i++)
                                <option value="{{ $i }}" @if($course_checkout_detail->no_of_people == $i) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="edit_booking" class="btn btn-info">Edit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>