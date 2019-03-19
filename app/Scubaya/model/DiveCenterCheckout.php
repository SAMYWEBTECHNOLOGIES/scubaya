<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class DiveCenterCheckout extends Model
{
    protected $fillable =   ['user_key','course_id','no_of_people','subtotal','divers','status'];

    public static function saveCourseCheckoutData($data)
    {
        $dive_center_checkout   =   new DiveCenterCheckout();
        foreach ($data as $key=>$value)
        {
            $dive_center_checkout->$key     =   $value;
        }
        $dive_center_checkout->save();
        return $dive_center_checkout;
    }

    public static function transferCookieCartToDatabase()
    {
        $user_id        =   Crypt::decrypt($_COOKIE['scubaya_dive_in']);
        $cart_items     =   unserialize(Crypt::decrypt($_COOKIE['courses']));

        Cookie::queue('courses',null, -1,'/');

        foreach ($cart_items as $id => $persons){
            $check_exist    =   DiveCenterCheckout::where([['user_key',$user_id],['course_id',$id],['status',CHECKOUT_PENDING]])->exists();
            if(!$check_exist){
                $data   =   [
                    'user_key'          =>  $user_id,
                    'course_id'         =>  $id,
                    'no_of_people'      =>  $persons,
                    'status'            =>  CHECKOUT_PENDING
                ];
                DiveCenterCheckout::saveCourseCheckoutData($data);
            }
        }
    }

    public static function insertSubtotalOfEveryCourse($user_id,$exchange_rate)
    {
        $courses_in_cart    =   DiveCenterCheckout::where([['user_key',$user_id],['status',CHECKOUT_PENDING]])->get();
        if($courses_in_cart){
            foreach ($courses_in_cart as $checkout_id){
                $course_detail  =   Courses::where('id',$checkout_id->course_id)->first(['course_pricing','merchant_key']);
                $course_pricing =   json_decode($course_detail->course_pricing)->price;
                $merchant_key   =   $course_detail->merchant_key;
                $no_of_people   =   $checkout_id->no_of_people;
                $subtotal       =   $no_of_people *(int)(($course_pricing) * $exchange_rate[$merchant_key]['rate']);
                DiveCenterCheckout::where([['user_key',$user_id],['status',CHECKOUT_PENDING],['course_id',$checkout_id->course_id]])->update(['subtotal'  =>  $subtotal]);
            }
        }
    }
}
