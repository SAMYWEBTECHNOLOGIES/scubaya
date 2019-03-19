@php
    $merchantId =   (new \Hashids\Hashids())->encode($product_detail->merchant_key);
    $bookingId  =   (new \Hashids\Hashids())->encode($product_checkout_detail->id);
@endphp

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="edit-product-modal{{@$product_checkout_detail->cart_id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">Edit {{ isset($product_detail->title) ? ucwords($product_detail->title) : '' }}</h4>
            </div>

            <div class="modal-body">
                <form name="edit_product_booking" method="post" action="{{ route('scubaya::user::bookings::edit_booking', [Auth::id(), $merchantId, $bookingId]) }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input name="item_type" type="hidden" value="product">
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <select class="form-control" name="quantity">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" @if($product_checkout_detail->quantity == $i) selected @endif>{{ $i }}</option>
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