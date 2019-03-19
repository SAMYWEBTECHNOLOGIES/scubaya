<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class ProductBookingRequest extends Model
{
    //
    protected $table        =   'product_booking_request';

    public static function saveProducts($data)
    {
        $productBookingRequest   =   new ProductBookingRequest();

        foreach ($data as $key => $value) {
            $productBookingRequest->$key     =   $value;
        }

        $productBookingRequest->save();

        //return $productBookingRequest;
    }

    public static function updateBookingRequestStatus($cartId, $status)
    {
        $bookingRequest =   self::query()->where('cart_id', $cartId)->first();

        $bookingRequest->status =   $status;
        $bookingRequest->update();

        return $bookingRequest;
    }
}
