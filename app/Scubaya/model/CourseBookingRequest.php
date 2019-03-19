<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class CourseBookingRequest extends Model
{
    protected $table        =   'course_booking_request';

    public static function saveCourses($data)
    {
        $courseBookingRequest   =   new CourseBookingRequest();

        foreach ($data as $key => $value) {
            $courseBookingRequest->$key     =   $value;
        }

        $courseBookingRequest->save();

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
