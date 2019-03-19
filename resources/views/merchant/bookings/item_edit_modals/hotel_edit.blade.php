@php
    $merchantId =   (new \Hashids\Hashids())->encode($hotel_detail->merchant_primary_id);
    $bookingId  =   (new \Hashids\Hashids())->encode($hotel_checkout_detail->id);
@endphp

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="edit-hotel-modal{{@$hotel_checkout_detail->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">Edit {{ isset($hotel_detail->tariff_title) ? ucwords($hotel_detail->tariff_title) : '' }}</h4>
            </div>

            <div class="modal-body">
                <form name="edit_hotel_booking" method="post" action="{{ route('scubaya::merchant::bookings::update_hotel_room_booking', [$merchantId, $bookingId]) }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input name="item_type" type="hidden" value="hotel">
                    </div>

                    <div class="form-group">
                        <label for="no_of_persons">No Of Persons:</label>
                        <select class="form-control" name="no_of_persons">
                            @for($i = $min_people; $i <= $max_people; $i++)
                                <option value="{{ $i }}" @if($hotel_checkout_detail->no_of_persons == $i) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="edit_booking" class="btn btn-info">Edit Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>