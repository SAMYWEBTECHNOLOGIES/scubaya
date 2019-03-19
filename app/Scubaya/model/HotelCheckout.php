<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class HotelCheckout extends Model
{
    protected $table    =   'hotel_checkout';

    protected $fillable =   ['user_key','check_in','check_out', 'tariff_id', 'guests','subtotal','status'];

    public static function saveHotelCheckoutData($data)
    {
        $hotelCheckout   =   new HotelCheckout();

        foreach ($data as $key=>$value)
        {
            $hotelCheckout->$key     =   $value;
        }

        $hotelCheckout->save();

        return $hotelCheckout;
    }

    public static function transferCookieCartToDatabase()
    {
        $userId         =   Crypt::decrypt($_COOKIE['scubaya_dive_in']);
        $cartItems      =   unserialize(Crypt::decrypt($_COOKIE['hotel']));

        Cookie::queue('hotel',null, -1,'/');

        foreach ($cartItems as $cart => $items){
            $check_exist    =   HotelCheckout::where([['user_key',$userId],['tariff_id',$cart],['status',CHECKOUT_PENDING]])->exists();
            if(!$check_exist){
                $data   =   [
                    'user_key'          =>  $userId,
                    'check_in'          =>  '',
                    'check_out'         =>  '',
                    'tariff_id'         =>  $cart,
                    'guests'            =>  $items,
                    'status'            =>  CHECKOUT_PENDING
                ];

                HotelCheckout::saveHotelCheckoutData($data);
            }
        }
    }
}
