<div class="modal fade bs-example-modal-lg" tabindex="-1" id="product-edit-request-modal{{@$product_checkout_detail->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header">
                <h4 class="blue">{{ isset($product_detail->title) ? ucwords($product_detail->title) : '' }}</h4>

                @if(!empty($product_detail->manufacturer))
                    <span class="meta">
                         {{ $product_detail->manufacturer }}
                    </span>
                @endif
            </div>

            <div class="modal-body">
                <h5>Original Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Weight</h5>
                        <p>{{ $product_detail->weight }} Kg</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Color </h5>
                        <span id="product-color" style="background-color: {{ $product_detail->color }}"></span>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Quantity</h5>
                        <p>{{ $product_checkout_detail->quantity }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Tax</h5>
                        <p>{{ $product_detail->tax or 0 }} %</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Price</h5>
                        <p>{{ $exchangeRate[$product_checkout_detail->merchant_key]['symbol'].$product_detail->price }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$product_checkout_detail->merchant_key]['symbol'].$product_checkout_detail->total }}</strong></p>
                    </div>
                </div>

                <hr>

                <h5>New Request</h5>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="meta">Weight</h5>
                        <p>{{ $product_detail->weight }} Kg</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Color </h5>
                        <span id="product-color" style="background-color: {{ $product_detail->color }}"></span>
                    </div>

                    @php
                        $editData       =   json_decode($edit_booking_data->params);
                    @endphp

                    <div class="col-md-2">
                        <h5 class="meta">Quantity</h5>
                        <p>{{ $editData->quantity }}</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Tax</h5>
                        <p>{{ $product_detail->tax or 0 }} %</p>
                    </div>

                    <div class="col-md-2">
                        <h5 class="meta">Price</h5>
                        <p>{{ $exchangeRate[$product_checkout_detail->merchant_key]['symbol'].$product_detail->price }}</p>
                    </div>

                    @php
                        $netAmount  =   $editData->quantity * ( $product_detail->price + ($product_detail->price * $product_detail->tax / 100 ) )
                    @endphp

                    <div class="col-md-2">
                        <h5 class="meta">Total</h5>
                        <p class="blue"><strong>{{ $exchangeRate[$product_checkout_detail->merchant_key]['symbol'].$netAmount }}</strong></p>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <a href="{{ route('scubaya::merchant::bookings::confirm_product_booking', [$authId, $edit_booking_data->id]) }}">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </a>

                    <a href="{{ route('scubaya::merchant::bookings::decline_product_booking', [$authId, $edit_booking_data->id]) }}">
                        <button name="decline_booking_request" class="btn btn-danger">Decline</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>