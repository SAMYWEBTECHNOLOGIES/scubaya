<?php

/**
 * 'scubaya_dive_in' is the cookie from the user, which is its user id
 *
 */

namespace App\Http\Controllers\Front;

use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Scubaya\model\Cart;
use App\Scubaya\model\Courses;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\Invoices;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantDiveCenterInvoicesMapper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class DiveCenterCheckoutController extends Controller
{
    /*ajax to add course in the cart*/
    public function addTocart(Request $request)
    {
        $cookie_id_check    =   $request->hasCookie('scubaya_dive_in');
        $course_id          =   Input::get('course_id');
        $no_of_persons      =   Input::get('no_of_persons');

        if ($cookie_id_check){
            $user_id    =   Cookie::get('scubaya_dive_in');
            if($request->hasCookie('courses')){
                DiveCenterCheckout::transferCookieCartToDatabase();
            }
            $check      =   Courses::where('id', $course_id)->exists();
            if ($check) {
                $course_already_in_cart = DiveCenterCheckout::where([
                    ['user_key', $user_id],
                    ['course_id', $course_id],
                    ['status', DIVE_CENTER_COURSE_PENDING]
                ])->exists();

                if (!$course_already_in_cart) {
                    $user_checkout_data = [
                        'user_key'          => $user_id,
                        'course_id'         => $course_id,
                        'no_of_people'      => $no_of_persons,
                        'status'            => DIVE_CENTER_COURSE_PENDING
                    ];

                    DiveCenterCheckout::saveCourseCheckoutData($user_checkout_data);
                    $count  =   DiveCenterCheckout::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING]])->count();
                    return response()->json(['status'   =>  'Course added to the cart.','count' => $count,'already' =>  0]);
                }
                return response()->json(['status'   =>  'Course already in cart!','already' =>  1]);
            }
        }else {
            $cart   =   $request->hasCookie('courses')? unserialize(Crypt::decrypt($_COOKIE['course'])) : [];

            if(array_key_exists($course_id,$cart)){
                return response()->json(['status'   =>  'Course already in cart!','already' =>  1]);
            }else{
                $cart[$course_id] = $no_of_persons;
                return response()->json(['status'   =>  'Course added to the cart.','already' =>  0,'count' => count($cart) ])->withCookie(Cookie::make('course',serialize($cart),60*24*30,'/'));
            }
        }
    }


    public function deleteCourseItem(Request $request)
    {
        if($request->hasCookie('scubaya_dive_in')){
            $user_id            =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id ], ['item_type',$request->type], ['item_id', $request->id]])->delete();
        }else{
            if($request->hasCookie('course')){
                $cart   =    unserialize(Crypt::decrypt($_COOKIE['course']));
                unset($cart[$request->id]);
                setcookie('course', Crypt::encrypt(serialize($cart)), time() + 86400 , '/');
            }
        }
        return redirect()->route('scubaya::checkout::cart');
    }


    public function changeNoOfDivers(Request $request)
    {
        $is_user_logged_in      =   $request->hasCookie('scubaya_dive_in');
        $course_id              =   Input::get('course_id');
        $no_of_persons          =   Input::get('no_of_persons');

        if($is_user_logged_in){
            $user_id    =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id],['item_type','course'], ['item_id', $course_id], ['status',CHECKOUT_PENDING]])
                ->update(['item_data'   =>  json_encode(['no_of_persons' => $no_of_persons])]);

            return response()->json(true);
        }else{
            $cart                   =   $request->hasCookie('course')? unserialize(Crypt::decrypt($_COOKIE['course'])) : [];
            $cart[$course_id]       =   [
                'no_of_persons'     =>  $no_of_persons
            ];
            return response()->json(true)->withCookie(Cookie::make('course', serialize($cart), 60*24, '/'));
        }
    }
}
