<div class="modal fade bs-example-modal-lg" tabindex="-1" id="course-modal{{@$cart_id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content padding-20">
            <div class="modal-header text-center">
                <h4 class="blue">{{ ucwords($course_detail->course_name) }}</h4>
                @if(!empty($course_detail->dive_center_address))
                    <span class="meta">
                         {{ $course_detail->dive_center_address }}
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
                    <span style="color: #5f5f5f;"><strong>Booking:</strong> {{ '#'.$course_checkout_detail->booking_id }}</span>
                </div>

                <div class=" course-name-location">
                    <h5 style="margin-bottom: 2px">{{ isset($course_detail->course_name) ? ucwords($course_detail->course_name) : '' }}</h5>

                    @php
                        $location       = json_decode($course_detail->location);

                        $courseDuration = json_decode($course_detail->course_days);

                        $coursePricing  = json_decode($course_detail->course_pricing);
                    @endphp

                    @if(!empty($location->address))
                        <span class="meta">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $location->address }}
                        </span>
                    @endif
                </div>

                <div class="row course-description padding-top-20">
                    <div class="col-md-12">
                        {!! @$course_detail->description !!}
                    </div>

                    @if($course_detail->cancellation_detail)
                        <div class="col-md-12">
                            <span class="meta"><strong>Cancellation Details:</strong></span>
                            {!! @$course_detail->cancellation_detail !!}
                        </div>
                    @endif
                </div>

                @php
                    $productsInclInCourse   =   array();
                    $productsExclInCourse   =   array();
                    $products               =   (array)json_decode($course_detail->products);

                    if(count($products)) {
                        foreach ($products as $key  =>  $value) {
                            if($value->required) {
                                if($value->IE) {
                                    $productName              =   \App\Scubaya\model\Products::where('id', $key)->first(['title']);
                                    $productsInclInCourse[]   =   $productName->title;
                                } else {
                                    $productName              =   \App\Scubaya\model\Products::where('id', $key)->first(['title']);
                                    $productsExclInCourse[]   =   $productName->title;
                                }
                            }
                        }
                    }
                @endphp

                @if(count($productsInclInCourse))
                <div class="row products-incl-in-course">
                    <div class="col-md-12">
                        <span class="meta"><strong>@if(count($productsInclInCourse) > 1) Products @else Product @endif Included: </strong></span>
                        @foreach($productsInclInCourse as $product)
                            {{ ucwords($product) }}
                            @if(!$loop->last) {{ ',' }} @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @if(count($productsExclInCourse))
                    <div class="row products-incl-in-course">
                        <div class="col-md-12">
                            <span class="meta"><strong>@if(count($productsExclInCourse) > 1) Products @else Product @endif Excluded: </strong></span>
                            @foreach($productsExclInCourse as $product)
                                {{ ucwords($product) }}
                                @if(!$loop->last) {{ ',' }} @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $affiliates   =   (array)json_decode($course_detail->affiliates);
                @endphp

                @if(count($affiliates))
                <div class="row course-affiliations padding-top-20">
                    <div class="col-md-12">
                        <span class="meta"><strong>Affiliations:</strong></span><br>
                        @foreach ($affiliates as $affiliate)
                            @php
                                $affiliatesDetail   =   \App\Scubaya\model\Affiliations::where('id', $affiliate)->first();
                            @endphp

                            <div class="affiliations-image-section">
                                <img data-tooltip="{{ $affiliatesDetail->name }}" data-inverted="" alt="{{ $affiliatesDetail->name }}"  src="{{ asset('assets/images/scubaya/affiliations/'.$affiliate.'-'.$affiliatesDetail->image) }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif


                <div class="row-eq-height padding-top-20">
                    <div class="col-xs-3 col-md-3 col-sm-3 cell-grid">
                        <h5>Duration</h5>
                        <div class="meta">  {{ $courseDuration->no_of_days or '---' }} days</div>
                    </div>

                    <div class="col-xs-3 col-md-3 col-sm-3 cell-grid">
                        <h5>No Of Persons</h5>
                        <div class="meta">
                            @if(($course_checkout_detail->no_of_people) > 3)
                                <i class="fa fa-user no-of-person"></i> x {{ $course_checkout_detail->no_of_people }}
                            @else
                                @for($i = 0; $i < $course_checkout_detail->no_of_people; $i++)
                                    <i class="fa fa-user no-of-person"></i>
                                @endfor
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-3 col-md-3 col-sm-3 cell-grid">
                        <h5>Price Per Person</h5>
                        <div class="meta">  {{ @$exchangeRate[$course_detail->merchant_key]['symbol'].number_format($exchangeRate[$course_detail->merchant_key]['rate'] * $coursePricing->price, 2) }} </div>
                    </div>

                    <div class="col-xs-3 col-md-3 col-sm-3 cell-grid">
                        <h5>Total</h5>
                        <div class="blue"><strong>{{ @$exchangeRate[$course_detail->merchant_key]['symbol'].number_format($course_checkout_detail->total * $exchangeRate[$course_detail->merchant_key]['rate'], 2) }}</strong> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>