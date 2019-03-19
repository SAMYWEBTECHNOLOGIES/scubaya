<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Scubaya\model\Cart;
use App\Scubaya\model\CourseBookingRequest;
use App\Scubaya\model\Courses;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\HotelBookingRequest;
use App\Scubaya\model\Invoices;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantDiveCenterInvoicesMapper;
use App\Scubaya\model\MerchantProductInvoicesMapper;
use App\Scubaya\model\Orders;
use App\Scubaya\model\ProductBookingRequest;
use App\Scubaya\model\ProductCheckouts;
use App\Scubaya\model\Products;
use App\Scubaya\model\RoomPricing;
use App\Scubaya\model\RoomPricingSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
    private $item_cart_ids  =   array();

    /*public function __construct(Request $request)
    {
        if(!Session::has('exchange-rate')){
            $merchantPrimaryIds =   ManageDiveCenter::select('merchant_key as merchant_primary_id')->groupBy('merchant_key')->get();
            $exchangeRateHelper =   new ExchangeRateHelper($request->ip(), $merchantPrimaryIds);
            $exchangeRate       =   $exchangeRateHelper->getExchangeRate();
            Session::put('exchange-rate',  $exchangeRate);
        }
    }*/

    public function cart(Request $request)
    {
        if(isset($_GET['error'])){
            $request->session()->put('error',$_GET['error']);
            return redirect(\Request::fullUrl());
        }

        if(!$request->hasCookie('scubaya_dive_in')){
            $_GET['show_popup'] =   true;
            $_GET['error']      =   "You have to login or Sign up first!";
        }

        if($request->session()->has('error')){
            $_GET['error']      =   $request->session()->get('error');
            $_GET['show_popup'] =   true;
            $request->session()->forget('error');
        }

        /*
         * If user is logged in then move all cookie
         * data of items to database
         */
        if($request->hasCookie('scubaya_dive_in')){
            if($request->hasCookie('course')){
                Cart::saveItemFromCookie('course');
            }

            if($request->hasCookie('product')){
                Cart::saveItemFromCookie('product');
            }

            if($request->hasCookie('hotel')){
                Cart::saveItemFromCookie('hotel');
            }
        }

        $courses       =    Courses::take(4)->get();

        return view('front.checkout.cart',[
            'Courses'       =>  $courses,
            'ip'            =>  $request->ip()
            //'exchangeRate'  =>  $request->session()->get('exchange-rate')
        ]);
    }

    public function addToCart(Request $request){
        $items          =   array();
        $itemType       =   array();
        $isItemExists   =   false;

        $isUserCookie       =   $request->hasCookie('scubaya_dive_in');
        $type               =   $request->get('type');

        if(strtolower($type)    ==  'hotel') {
            $items['id']        =   $request->get('tariff_id');
            $items['data']      =   [
                'check_in'      =>  $request->get('check_in'),
                'check_out'     =>  $request->get('check_out'),
                'no_of_persons' =>  $request->get('no_of_persons'),
                'price'         =>  $request->get('price'),
            ];

            $itemType            =   'Room';
            $isItemExists        =   RoomPricing::where('id', $items['id'])->exists();
        }

        if(strtolower($type)    ==  'course') {
            $items['id']          =   $request->get('course_id');
            $items['data']        =   [
                'no_of_persons'   =>  $request->get('no_of_persons')
            ];

            $itemType            =   'Course';
            $isItemExists        =   Courses::where('id', $items['id'])->exists();
        }

        if(strtolower($type)    ==  'product') {
            $items['id']         =   $request->get('id');
            $items['data']       =   [
                'quantity'       => $request->get('quantity')
            ];

            $itemType            =   'Product';
            $isItemExists        =    Products::where('id', $items['id'])->exists();
        }

        if ($isUserCookie){
            $userId    =   Cookie::get('scubaya_dive_in');

            if ($isItemExists) {
                $itemAlreadyInCart =   Cart::where([
                    ['user_key', $userId],
                    ['item_type', $type],
                    ['item_id', $items['id']],
                    ['status', CHECKOUT_PENDING]
                ])->exists();

                if (!$itemAlreadyInCart) {
                    $userCheckoutData = [
                        'user_key'  =>  $userId,
                        'item_type' =>  $type,
                        'item_id'   =>  $items['id'],
                        'item_data' =>  json_encode($items['data']),
                        'status'    =>  CHECKOUT_PENDING
                    ];

                    Cart::saveItem($userCheckoutData);

                    $count  =   Cart::where([['user_key',$userId],['status',CHECKOUT_PENDING]])->count();

                    return response()->json(['status'   =>  $itemType.' added to the cart.','count' => $count,'already' =>  0]);
                }

                return response()->json(['status'   =>  $itemType.' already in cart!','already' =>  1]);
            }
        } else {
            $cart   =   $request->hasCookie($type)? unserialize(Crypt::decrypt($_COOKIE[$type])) : [];

            if(!empty($cart) && array_key_exists($items['id'], $cart)) {
                return response()->json(['status'   =>  $itemType.' already in cart!','already' =>  1]);
            } else {
                $cart[$items['id']] =   $items['data'];
                return response()->json(['status'   =>  $itemType.' added to the cart.','already' =>  0,'count' => count($cart) ])->withCookie(Cookie::make($type,serialize($cart),60*24,'/'));
            }
        }
    }

    public function orderReview(Request $request)
    {
        /*if the user is not logged in then it has to redirect*/
        if(!$request->hasCookie('scubaya_dive_in')){
            return redirect()->back();
        }

        $user_id        =   Cookie::get('scubaya_dive_in');

        /*when user fill the diver details or hit the proceed to checkout button*/
        if($request->isMethod('order_review')){
            $products      =   Cart::where([['user_key',$user_id],['item_type','product'],['status',CHECKOUT_PENDING]])->get();
            $courses       =   Cart::where([['user_key',$user_id],['item_type','course'],['status',CHECKOUT_PENDING]])->get();
            $hotels        =   Cart::where([['user_key',$user_id],['item_type','hotel'],['status',CHECKOUT_PENDING]])->get();

            /*if any product is booked */
            if(count($products)){
                $this->_saveProductsBookingRequest($products, $user_id);
            }

            /*if any course is booked */
            if(count($courses)){
               $this->_saveCoursesBookingRequest($courses, $user_id, $request->details);
            }

            /* if any hotel room is booked*/
            if(count($hotels)) {
                $this->_saveHotelRoomBookingRequest($hotels, $user_id);
            }

            /* save order details*/
            $this->_saveOrderDetails($user_id);

            return redirect()->route('scubaya::checkout::thank_you');
        }

        $courses_in_cart    =   Cart::where([['user_key',$user_id],['item_type','course'],['status',CHECKOUT_PENDING]])->get();
        $products_in_cart   =   Cart::where([['user_key',$user_id],['item_type','product'],['status',CHECKOUT_PENDING]])->get();
        $hotels_in_cart     =   Cart::where([['user_key',$user_id],['item_type','hotel'],['status',CHECKOUT_PENDING]])->get();

        return view('front.checkout.order_review')->with([
                    'courses_in_cart'   =>  $courses_in_cart,
                    'products_in_cart'  =>  $products_in_cart,
                    'hotels_in_cart'    =>  $hotels_in_cart,
                    'ip'                =>  $request->ip()
        ]);
    }

    protected function _saveProductsBookingRequest($products, $user_id)
    {
        /* mark pending products to completed in cart table */
        Cart::where([['user_key',$user_id],['item_type','product'],['status',CHECKOUT_PENDING]])
            ->update(['status'    =>  CHECKOUT_COMPLETED]);

        /*calculate the total of the product and insert the detail of every product into the product_details array*/
        foreach($products as $product) {
            $productDetail = Products::where('id', $product->item_id)->first(['id', 'merchant_key', 'price', 'tax']);
            array_push($this->item_cart_ids, $product->id);

            $item_data  = json_decode($product->item_data);
            $quantity   = array_key_exists('quantity', $item_data) ? (int)$item_data->quantity : 1;

            /* if product has tax */
            if($productDetail->tax) {
                $productDetail->price   =   $productDetail->price + ($productDetail->price * ($productDetail->tax) / 100);
            }

            $products = [
                'cart_id'       => $product->id,
                'booking_id'    => $this->_getBookingId(Carbon::now()->format('Y-m-d')),
                'merchant_key'  => $productDetail->merchant_key,
                'product_id'    => $product->item_id,
                'quantity'      => $quantity,
                'status'        => NEW_BOOKING_REQUEST,
                'total'         => $quantity * $productDetail->price
            ];

            ProductBookingRequest::saveProducts($products);
        }
    }

    protected function   _saveCoursesBookingRequest($courses, $user_id, $diverDetails)
    {
        /* mark pending courses to completed in cart table */
        Cart::where([['user_key',$user_id],['item_type','course'],['status',CHECKOUT_PENDING]])
            ->update(['status'    =>  CHECKOUT_COMPLETED]);

        /*calculate the total of the all course and push every course detail in the courses_details array*/
        foreach($courses as $course){
            $courseDetail =  Courses::where('id',$course->item_id)->select('merchant_key','course_pricing', 'course_start_date')->first();
            array_push($this->item_cart_ids,$course->id);

            $item_data    =   (array)json_decode($course->item_data);
            $no_of_people =   array_key_exists('no_of_persons',$item_data) ? $item_data['no_of_persons'] : 1;

            /* push divers details for course booking */
            $diver_details  =   array();
            foreach ($diverDetails as $course_id => $diver_detail){
                if($course_id == $course->item_id){
                    array_push($diver_details, $diver_detail);
                }
            }

            $course_price    = json_decode($courseDetail->course_pricing)->price;

            $courses = [
                'cart_id'       => $course->id,
                'booking_id'    => $this->_getBookingId(Carbon::now()->format('Y-m-d')),
                'merchant_key'  => $courseDetail->merchant_key,
                'course_id'     => $course->item_id,
                'no_of_people'  => $no_of_people,
                'divers'        => json_encode($diver_details),
                'status'        => NEW_BOOKING_REQUEST,
                'total'         => $no_of_people *  $course_price
            ];

            CourseBookingRequest::saveCourses($courses);
        }
    }

    protected function _saveHotelRoomBookingRequest($hotels, $user_id)
    {
        /* mark pending hotel room to completed in cart table */
        Cart::where([['user_key',$user_id],['item_type','hotel'],['status',CHECKOUT_PENDING]])
            ->update(['status'    =>  CHECKOUT_COMPLETED]);

        /*calculate the total of the room and insert the detail of every hotel room into the booking table*/
        foreach($hotels as $hotel) {
            $hotel_detail    =   \App\Scubaya\model\RoomPricing::where('room_pricing.id', '=', $hotel->item_id)
                                            ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                            ->select( 'room_details.merchant_primary_id as merchant_key', 'room_pricing.additional_tariff_data')
                                            ->first();

            array_push($this->item_cart_ids, $hotel->id);

            $item_data  =   json_decode($hotel->item_data);

            // To check ignore per person per night setting
            // if it is on then include no of persons in price calculation
            // else include min nights
            $tariffData =   (array)json_decode($hotel_detail->additional_tariff_data);

            if(array_key_exists('micro', $tariffData)) {
                if($tariffData['micro']->ignore_pppn) {
                    $totalPrice =   $item_data->price;
                } else {
                    $totalPrice =   $item_data->price * $item_data->no_of_persons;
                }
            }

            if(array_key_exists('normal', $tariffData)) {
                /* To check global per person per night option */
                $pricingSetting  =   RoomPricingSettings::where('merchant_primary_id', $hotel_detail->merchant_key)
                                                                            ->first(['currency']);

                $pricingSetting  =   json_decode($pricingSetting->currency);

                if($pricingSetting->prices_pppn) {
                    $totalPrice  =   $item_data->price * $item_data->no_of_persons;
                } else {
                    $totalPrice  =   $item_data->price;
                }
            }

            if(array_key_exists('advance', $tariffData)) {
                if($tariffData['advance']->ignore_pppn) {
                    $totalPrice                     =   $item_data->price;
                } else {
                    $totalPrice                     =   $item_data->price * $item_data->no_of_persons;
                }
            }

            $hotels = [
                'cart_id'       => $hotel->id,
                'booking_id'    => $this->_getBookingId(Carbon::now()->format('Y-m-d')),
                'merchant_key'  => $hotel_detail->merchant_key,
                'tariff_id'     => $hotel->item_id,
                'check_in'      => Carbon::createFromFormat('d-m-Y', $item_data->check_in)->format('Y-m-d'),
                'check_out'     => Carbon::createFromFormat('d-m-Y', $item_data->check_out)->format('Y-m-d'),
                'no_of_persons' => $item_data->no_of_persons,
                'status'        => NEW_BOOKING_REQUEST,
                'total'         => $totalPrice
            ];

            HotelBookingRequest::saveHotels($hotels);
        }
    }

    protected function _saveOrderDetails($user_id)
    {
        if($this->item_cart_ids){
            $order = [
                'order_id'      => (int)date('ymdHi').random_int(100,10000),
                'items'         => json_encode($this->item_cart_ids),
                'user_key'      => $user_id
            ];

            Orders::saveOrders($order);
        }
    }

    protected function _getBookingId($date)
    {
        if($date) {
            $date = date('ymd', strtotime($date));
        }

        $randomNo   =   str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);

        return $date ? $date.$randomNo : str_pad($randomNo, 10, 0, STR_PAD_LEFT);
    }

    public function thankYou()
    {
        return view('front.checkout.thank_you');
    }
}
