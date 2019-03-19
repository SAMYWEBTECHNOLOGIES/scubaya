<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class HotelBookingRequest extends Model
{
    protected $table        =   'hotel_booking_request';

    public static function saveHotels($data)
    {
        $hotelBookingRequest   =   new HotelBookingRequest();

        foreach ($data as $key => $value) {
            $hotelBookingRequest->$key     =   $value;
        }

        $hotelBookingRequest->save();

        //return $courseBookingRequest;
    }

    public static function updateBookingRequestStatus($cartId, $status)
    {
        $bookingRequest =   self::query()->where('cart_id', $cartId)->first();

        $bookingRequest->status =   $status;
        $bookingRequest->update();

        return $bookingRequest;
    }
}
