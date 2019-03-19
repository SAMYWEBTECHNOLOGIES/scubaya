<div class="modal fade bs-example-modal-lg" tabindex="-1" id="hotel-modal{{@$hotel_checkout_detail->cart_id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header text-center">
                <h4 class="blue">{{ @ucwords($hotel_detail->name) }}</h4>
                @if(!empty($hotel_detail->address))
                    <span class="meta">
                        {{ $hotel_detail->address }}
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
                    <span style="color: #5f5f5f;"><strong>Booking:</strong> {{ '#'.$hotel_checkout_detail->booking_id }}</span>
                </div>

                <div class=" tariff-description">
                   <h5 style="margin-bottom: 2px">{{ ucwords($hotel_detail->tariff_title) }}</h5>
                   {!! $hotel_detail->tariff_description !!}
                </div>

                @php
                    $features   =   (array)json_decode($hotel_detail->features);
                @endphp

                @if(count($features))
                <div class="room-features">
                    <span class="meta"><strong>Room @if(count($features) > 1) Features @else Feature @endif: </strong></span>
                    @foreach($features as $feature)
                        {{ ucwords($feature) }}
                        @if(!$loop->last) {{ ',' }} @endif
                    @endforeach
                </div>
                @endif

                <div class="row-eq-height padding-top-20">
                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>No Of Persons</h5>
                        <div class="meta">
                            @if(($hotel_checkout_detail->no_of_persons) > 3)
                                <i class="fa fa-user no-of-person"></i> x {{ $hotel_checkout_detail->no_of_persons }}
                            @else
                                @for($i = 0; $i < $hotel_checkout_detail->no_of_persons; $i++)
                                    <i class="fa fa-user no-of-person"></i>
                                @endfor
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>{{ $label }}</h5>
                        <div class="meta">
                            {{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{number_format($total_price * $exchangeRate[$hotel_detail->merchant_key]['rate'], 2)}}
                        </div>
                    </div>

                    @php
                        if($pppn) {
                            $netAmount  =   $totalPrice * $exchangeRate[$hotel_detail->merchant_key]['rate'] * $hotel_checkout_detail->no_of_persons;
                        } else {
                            $netAmount  =   $totalPrice * $exchangeRate[$hotel_detail->merchant_key]['rate'];
                        }
                    @endphp

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Total</h5>
                        <div class="blue">
                            <strong>{{@$exchangeRate[$hotel_detail->merchant_key]['symbol']}}{{number_format($netAmount, 2)}}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>