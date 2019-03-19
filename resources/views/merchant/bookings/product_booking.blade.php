<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            @if(count($productBookings))
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Weight</th>
                    <th>Color</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Tax</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($productBookings as $productBooking)
                    @php
                        $productDetail      =   \App\Scubaya\model\Products::where('id', $productBooking->product_id)->first();

                        $editBookingData    =   \App\Scubaya\model\EditBooking::where('booking_id', $productBooking->id)
                                                                        ->where([
                                                                        'table_name' => 'ProductBookingRequest',
                                                                        'status'     => 'pending'
                                                                        ])
                                                                        ->first();

                        $userInfo           =   \App\Scubaya\model\Cart::where('cart.id', $productBooking->cart_id)
                                                                    ->join('users', 'users.id', '=', 'cart.user_key')
                                                                    ->first(['first_name', 'last_name', 'email']);
                    @endphp
                    <tr>
                        <td>{{ $sno++ }}</td>
                        <td>{{ ucwords($productDetail->title) }}</td>
                        <td>{{ $productDetail->weight }} Kg</td>
                        <td><span id="product-color" style="background-color: {{ $productDetail->color }}"></span></td>
                        <td>{{ $productDetail->sku }}</td>
                        <td>{{ $productBooking->quantity }}</td>
                        <td>{{ $productDetail->tax or 0}} %</td>
                        <td>{{ $exchangeRate[$productBooking->merchant_key]['symbol'].$productDetail->price }}</td>
                        <td class="blue"><strong>{{ $exchangeRate[$productBooking->merchant_key]['symbol'].$productBooking->total }}</strong></td>
                        <td>
                            <select name="product_booking_status" class="form-control product_booking_status" id="{{ (new \Hashids\Hashids())->encode($productBooking->cart_id) }}">
                                <option value="new" @if($productBooking->status == 'new') selected @endif>New</option>
                                <option value="pending" @if($productBooking->status == 'pending') selected @endif>Pending</option>
                                <option value="cancelled" @if($productBooking->status == 'cancelled') selected @endif>Cancelled</option>
                                <option value="completed" @if($productBooking->status == 'completed') selected @endif>Completed</option>
                                <option value="confirmed" @if($productBooking->status == 'confirmed') selected @endif>Confirmed</option>
                                <option value="expired" @if($productBooking->status == 'expired') selected @endif>Expired</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class=" btn btn-success" data-toggle="modal" data-target="#product-modal{{ $productBooking->id }}">
                                <i class="fa fa-eye"></i>
                            </button>

                            <button type="button" class="button-blue btn btn-primary" data-toggle="modal" data-target="#edit-product-modal{{ $productBooking->id }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </td>
                        @if(! empty($editBookingData) && $editBookingData->status == 'pending')
                            <td>
                                <button type="button" class="btn-edit-request btn btn-primary" data-toggle="modal" data-target="#product-edit-request-modal{{$productBooking->id}}">
                                    Edit Request
                                </button>
                            </td>
                        @else
                            <td></td>
                        @endif
                    </tr>

                    {{-- product view modal --}}
                    @include('merchant.bookings.item_modals.product_modal', [
                        'product_detail'             =>  $productDetail,
                        'product_checkout_detail'    =>  $productBooking,
                        'user_info'                  =>  $userInfo
                    ])

                    {{-- include product edit booking request modal if there is any edit booking request for it --}}
                    @if($editBookingData)
                        @include('merchant.bookings.item_edit_request_modals.product_edit_request', [
                            'product_detail'             =>  $productDetail,
                            'product_checkout_detail'    =>  $productBooking,
                            'edit_booking_data'          =>  $editBookingData
                        ])
                    @endif

                    {{-- product edit modal --}}
                    @include('merchant.bookings.item_edit_modals.product_edit', [
                        'product_detail'             =>  $productDetail,
                        'product_checkout_detail'    =>  $productBooking,
                        'user_info'                 =>  $userInfo
                    ])
                @endforeach
            </tbody>
            @else
                <tr>
                    <th class="text-center">No Product Booking Found!</th>
                </tr>
            @endif
        </table>
    </div>
</div>