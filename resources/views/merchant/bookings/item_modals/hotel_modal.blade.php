<div class="modal fade bs-example-modal-lg" tabindex="-1" id="hotel-modal{{@$hotel_checkout_detail->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content padding-20">
            <div class="modal-body">
                <div class="row">
                    @if(isset($user_info))
                    <div class="col-md-8">
                        <div class="user-details">
                            <h5>User Details:</h5>
                            <div class="row">
                                <div class="col-md-1">
                                    <p class="meta p-margin-0">Name: </p>
                                </div>
                                <div class="col-md-11">
                                    <p class="p-margin-0">{{ ucwords(decrypt($user_info->first_name).' '.decrypt($user_info->last_name)) }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-1">
                                    <p class="meta p-margin-0">Email: </p>
                                </div>
                                <div class="col-md-11">
                                    <p class="p-margin-0">{{ decrypt($user_info->email) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div style="padding-top: 5px;" class="pull-right">
                            <span style="color: #5f5f5f;"><strong>Booking:</strong> {{ '#'.$hotel_checkout_detail->booking_id }}</span>
                        </div>
                    </div>
                    @else
                        <div class="col-md-12">
                            <div style="padding-top: 5px;" class="pull-right">
                                <span style="color: #5f5f5f;"><strong>Booking:</strong> {{ '#'.$hotel_checkout_detail->booking_id }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="hotel-name-location padding-top-20">
                    <h4 class="blue">{{ @ucwords($hotel_detail->name) }}</h4>
                    @if(!empty($hotel_detail->address))
                        <span class="meta">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $hotel_detail->address }}
                    </span>
                    @endif
                </div>

                <div class=" tariff-description">
                   <h5>{{ ucwords($hotel_detail->tariff_title) }}</h5>
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
                            {{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{number_format($price * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'], 2)}}
                        </div>
                    </div>

                    <div class="col-xs-4 col-md-4 col-sm-4 cell-grid">
                        <h5>Total</h5>
                        <div class="blue">
                            <strong>{{@$exchangeRate[$hotel_detail->merchant_primary_id]['symbol']}}{{number_format($hotel_checkout_detail->total * $exchangeRate[$hotel_detail->merchant_primary_id]['rate'], 2)}}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>