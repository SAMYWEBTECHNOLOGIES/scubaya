<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class Cart extends Model
{
    protected $table    =   'cart';

    protected $fillable =   ['user_key', 'item_type', 'item_id', 'item_data', 'status'];

    public static function saveItem($data)
    {
        $item   =   new Cart();

        foreach ($data as $key  =>  $value) {
            $item->$key =   $value;
        }

        $item->save();
    }

    public static function saveItemFromCookie($type)
    {
        $userId         =   Crypt::decrypt($_COOKIE['scubaya_dive_in']);
        $cartItems      =   unserialize(Crypt::decrypt($_COOKIE[$type]));

        Cookie::queue($type, null, -1, '/');

        foreach ($cartItems as $cart => $items){
            $check_exist    =   Cart::where([['user_key',$userId],['item_id',$cart],['status',CHECKOUT_PENDING]])->exists();

            if(!$check_exist){
                $data   =   [
                    'user_key'          =>  $userId,
                    'item_type'         =>  $type,
                    'item_id'           =>  $cart,
                    'item_data'         =>  json_encode($items),
                    'status'            =>  CHECKOUT_PENDING
                ];

                Cart::saveItem($data);
            }
        }
    }

    public static function insertSubtotalOfEveryProduct($user_id,$exchange_rate)
    {
        $products_in_cart   =   Cart::where([['user_key',$user_id],['status',CHECKOUT_PENDING]])->get();

        if($products_in_cart){
            foreach ($products_in_cart as $checkout_id){
                $product_detail =   Products::where('id',$checkout_id->product_id)->first(['price','merchant_key']);

                $merchant_key   =   $product_detail->merchant_key;

                $subtotal       =   (int)(($product_detail->price) * $exchange_rate[$merchant_key]['rate']);
                Cart::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING],['product_id',$checkout_id->product_id]])->update(['subtotal'  =>  $subtotal]);
            }
        }
    }

    public static function insertSubtotalOfEveryCourse($user_id,$exchange_rate)
    {
        $courses_in_cart    =   DiveCenterCheckout::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING]])->get();
        if($courses_in_cart){
            foreach ($courses_in_cart as $checkout_id){
                $course_detail  =   Courses::where('id',$checkout_id->course_id)->first(['course_pricing','merchant_key']);
                $course_pricing =   json_decode($course_detail->course_pricing)->price;
                $merchant_key   =   $course_detail->merchant_key;
                $no_of_people   =   $checkout_id->no_of_people;
                $subtotal       =   $no_of_people *(int)(($course_pricing) * $exchange_rate[$merchant_key]['rate']);
                Cart::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING],['course_id',$checkout_id->course_id]])->update(['subtotal'  =>  $subtotal]);
            }
        }
    }
}
