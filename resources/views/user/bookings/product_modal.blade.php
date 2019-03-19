<div class="modal fade bs-example-modal-lg" tabindex="-1" id="product-modal{{@$cart_id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header text-center">
                 <h4 class="blue">{{ ucwords($product_detail->name) }}</h4>
                @if(!empty($product_detail->address))
                    <span class="meta">
                         {{ $product_detail->address }}
                    </span>
                @endif
                {{--<br>
                <div style="padding-top: 5px;">
                    @if($subAccountInfo->phone_no)
                        <span class="meta"><strong>Phone No:</strong> {{ $subAccountInfo->phone_no }}</span> |
                    @endif
                    <span class="meta"><strong>Email:</strong> {{ $subAccountInfo->email }}</span>
                </div>--}}
            </div>

            <div class="modal-body">
                <div style="padding-top: 5px;">
                    <span style="color: #5f5f5f;"><strong>Booking:</strong> {{ '#'.$product_checkout_detail->booking_id }}</span>
                </div>

                <div class="product-name-manufacturer">
                    <h5 style="margin-bottom: 2px">{{ @ucwords($product_detail->title) }}</h5>
                    @if(!empty($product_detail->manufacturer))
                        <span class="meta">
                        Manufactured By {{ ucwords($product_detail->manufacturer) }}
                    </span>
                    @endif
                </div>

                <div class="row product-description padding-top-20">
                    <div class="col-md-12">
                        {!! @$product_detail->description !!}
                    </div>
                </div>

                <div class="product-other-info padding-top-20">
                    <div class="row">
                        <div class="col-xs-4 col-sm-6 col-md-2">
                             <label>WEIGHT: </label>
                        </div>
                        <div class="col-xs-8 col-sm-6 col-md-10">
                            {{ @$product_detail->weight }} Kg
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-sm-6 col-md-2">
                             <label>COLOR: </label>
                        </div>
                        <div class="col-xs-8 col-sm-6 col-md-10">
                            <span id="product_color" style="background-color: {{ @$product_detail->color }}"></span>
                        </div>
                    </div>
                </div>

                <div class="row-eq-height padding-top-20">
                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Quantity</h5>
                        <div class="meta">{{ @$product_checkout_detail->quantity }}</div>
                    </div>

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Price</h5>
                        <div class="meta">{{ @$exchangeRate[$product_detail->merchant_key]['symbol'].number_format($exchangeRate[$product_detail->merchant_key]['rate'] * $product_detail->price, 2) }}</div>
                    </div>

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Tax</h5>
                        <div class="meta">{{  $product_detail->tax or 0 }} %</div>
                    </div>

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Total</h5>
                        <div class="blue"><strong>{{ @$exchangeRate[$product_detail->merchant_key]['symbol'].number_format($product_checkout_detail->total * $exchangeRate[$product_detail->merchant_key]['rate'], 2) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>