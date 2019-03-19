<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EditBooking extends Model
{
    use Notifiable;

    protected $table        =   'edit_booking';

    protected $fillable     =   ['booking_id', 'table_name', 'params'];

    public static function saveBooking($data)
    {
        $booking   =   new EditBooking();

        foreach($data as $key => $value){
            $booking->$key =   $value;
        }
        $booking->save();

        return $booking;
    }
}
