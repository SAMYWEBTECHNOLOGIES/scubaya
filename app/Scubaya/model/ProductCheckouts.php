<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class ProductCheckouts extends Model
{
    public static function saveProductCheckoutData($data)
    {
        $product_checkout   =   new ProductCheckouts();
        foreach ($data as $key=>$value){
            $product_checkout->$key     =   $value;
        }
        $product_checkout->save();
        return $product_checkout;
    }

    public static function transferCookieCartToDatabase()
    {
        $user_id        =   Crypt::decrypt($_COOKIE['scubaya_dive_in']);
        $cart_items     =   unserialize(Crypt::decrypt($_COOKIE['products']));

        Cookie::queue('products',null, -1,'/');

        foreach ($cart_items as $id => $persons){
            $check_exist    =   ProductCheckouts::where([['user_key',$user_id],['product_id',$id],['status',DIVE_CENTER_COURSE_PENDING]])->exists();
            if(!$check_exist){
                $data   =   [
                    'user_key'          =>  $user_id,
                    'product_id'        =>  $id,
                    'quantity'          =>  $persons,
                    'status'            =>  DIVE_CENTER_COURSE_PENDING
                ];
                ProductCheckouts::saveProductCheckoutData($data);
            }
        }
    }


}
