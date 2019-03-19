<?php

namespace App\Http\Controllers\User;

use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\CourseBookingRequest;
use App\Scubaya\model\EditBooking;
use App\Scubaya\model\HotelBookingRequest;
use App\Scubaya\model\Invoices;
use App\Scubaya\model\MerchantDiveCenterInvoicesMapper;

use App\Scubaya\model\MerchantProductInvoicesMapper;
use App\Scubaya\model\Orders;
use App\Scubaya\model\ProductBookingRequest;
use App\Scubaya\model\User;
use Hashids\Hashids;
use Illuminate\Support\Facades\Hash;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myBookings(Request $request)
    {
        $myBookings         =   array();
        $merchantIds        =   array();
        $showInvoice        =   array();

        $orders             =   Orders::where('user_key',Auth::id())->orderByDesc('created_at')->get();

        if(count($orders)){
            foreach($orders as $order){
                $order_items    = (array)json_decode($order->items);

                foreach($order_items as $order_item){
                    if( \App\Scubaya\model\CourseBookingRequest::where('cart_id',$order_item)->exists()) {
                        $courses    =   \App\Scubaya\model\CourseBookingRequest::where('cart_id',$order_item)
                                                                                ->first(['merchant_key', 'course_id', 'cart_id', 'status']);

                        if($courses->status ==  CONFIRMED_BOOKING_REQUEST || $courses->status ==  COMPLETED_BOOKING_REQUEST) {
                            $showInvoice[$order->id][$courses->merchant_key]    =   true;
                        }

                        $myBookings[$order->id][$courses->merchant_key]['courses'][]   =   [
                                'course_id'     =>   $courses->course_id,
                                'cart_id'       =>   $courses->cart_id,
                        ];

                        if(!in_array($courses->merchant_key, $merchantIds))
                            $merchantIds[]    =   $courses->merchant_key;
                    }

                    if(\App\Scubaya\model\ProductBookingRequest::where('cart_id',$order_item)->exists()) {
                        $products   =   \App\Scubaya\model\ProductBookingRequest::where('cart_id',$order_item)
                                                                                ->first(['merchant_key', 'product_id', 'cart_id', 'status']);

                        if($products->status ==  CONFIRMED_BOOKING_REQUEST || $products->status ==  COMPLETED_BOOKING_REQUEST) {
                            $showInvoice[$order->id][$products->merchant_key]    =   true;
                        }

                        $myBookings[$order->id][$products->merchant_key]['products'][]   =   [
                            'product_id'    =>   $products->product_id,
                            'cart_id'       =>   $products->cart_id,
                        ];

                        if(!in_array($products->merchant_key, $merchantIds))
                        $merchantIds[]    =   $products->merchant_key;
                    }

                    if(\App\Scubaya\model\HotelBookingRequest::where('cart_id',$order_item)->exists()) {
                        $hotels   =   \App\Scubaya\model\HotelBookingRequest::where('cart_id',$order_item)
                                                                            ->first(['merchant_key', 'tariff_id', 'cart_id', 'status']);

                        if($hotels->status ==  CONFIRMED_BOOKING_REQUEST || $hotels->status ==  COMPLETED_BOOKING_REQUEST) {
                            $showInvoice[$order->id][$hotels->merchant_key]    =   true;
                        }

                        $myBookings[$order->id][$hotels->merchant_key]['hotels'][]   =   [
                            'tariff_id'     =>   $hotels->tariff_id,
                            'cart_id'       =>   $hotels->cart_id,
                        ];

                        if(!in_array($hotels->merchant_key, $merchantIds))
                            $merchantIds[]    =   $hotels->merchant_key;
                    }
                }
            }
        }

        $exchangeRate   =   (new ExchangeRateHelper($request->ip(), (object)$merchantIds))->getExchangeRate();

        return view('user.bookings.my_bookings')
                    ->with('bookings',$myBookings)
                    ->with('showInvoice', $showInvoice)
                    ->with('exchangeRate', $exchangeRate);
    }

    protected function _prepareEditCourseBooking($request, $bookingId)
    {
        $booking    =   new \stdClass();

        $booking->booking_id    =   $bookingId[0];
        $booking->params        =   json_encode([
            'no_of_persons' =>  $request->get('no_of_persons')
        ]);
        $booking->table_name    =   'CourseBookingRequest';

        return $booking;
    }

    protected function _prepareEditProductBooking($request, $bookingId)
    {
        $booking    =   new \stdClass();

        $booking->booking_id    =   $bookingId[0];
        $booking->params        =   json_encode([
            'quantity'  =>  $request->get('quantity')
        ]);
        $booking->table_name    =   'ProductBookingRequest';

        return $booking;
    }

    protected function _prepareEditHotelBooking($request, $bookingId)
    {
        $booking    =   new \stdClass();

        $booking->booking_id    =   $bookingId[0];
        $booking->params        =   json_encode([
            'no_of_persons' =>  $request->get('no_of_persons')
        ]);
        $booking->table_name    =   'HotelBookingRequest';

        return $booking;
    }

    public function editBooking(Request $request)
    {
        $merchantId =   (new Hashids())->decode($request->merchant_id);
        $bookingId  =   (new Hashids())->decode($request->booking_id);

        $merchantInfo   =   User::where(['id' => $merchantId, 'is_merchant' => IS])->first(['email', 'first_name', 'last_name']);

        if($request->get('item_type') == 'course') {
            $booking        =   EditBooking::saveBooking($this->_prepareEditCourseBooking($request, $bookingId));

            $courseInfo     =   CourseBookingRequest::join('courses', 'courses.id', '=', 'course_booking_request.course_id')
                                                    ->where('course_booking_request.id', $bookingId[0])->first(['courses.course_name']);

            $noOfPersons    =   (json_decode($booking->params))->no_of_persons;

            $template   =   'email.default';
            $subject    =   trans('email.edit_booking_subject', [ 'item_type' => $request->get('item_type')]);
            $message    =   trans('email.edit_course_booking_message',[
                'user'          =>  decrypt(Auth::user()->email),
                'no_of_persons' =>  $noOfPersons,
                'course'        =>  $courseInfo->course_name
            ]);

            $mail_helper    =   new SendMailHelper(env('MAIL_FROM_ADDRESS'), $merchantInfo->email, $template, $subject, $message);
            $mail_helper->sendMail();

            $request->session()->flash('status', 'Your course edit request has been sent to '.$merchantInfo->first_name. ' '.$merchantInfo->last_name.'.');
        }

        if($request->get('item_type') == 'product') {
            $booking        =   EditBooking::saveBooking($this->_prepareEditProductBooking($request, $bookingId));

            $productInfo    =   ProductBookingRequest::join('products', 'products.id', '=', 'product_booking_request.product_id')
                                                    ->where('product_booking_request.id', $bookingId[0])->first(['products.title']);

            $quantity   =   (json_decode($booking->params))->quantity;

            $template   =   'email.default';
            $subject    =   trans('email.edit_booking_subject', [ 'item_type' => $request->get('item_type')]);
            $message    =   trans('email.edit_product_booking_message',[
                'user'     =>  decrypt(Auth::user()->email),
                'quantity' =>  $quantity,
                'product'  =>  $productInfo->title
            ]);

            $mail_helper    =   new SendMailHelper(env('MAIL_FROM_ADDRESS'), $merchantInfo->email, $template, $subject, $message);
            $mail_helper->sendMail();

            $request->session()->flash('status', 'Your product edit request has been sent to '.$merchantInfo->first_name. ' '.$merchantInfo->last_name.'.');
        }

        if($request->get('item_type') == 'hotel') {
            $booking        =   EditBooking::saveBooking($this->_prepareEditHotelBooking($request, $bookingId));

            $hotelInfo      =   HotelBookingRequest::join('room_pricing', 'room_pricing.id', '=', 'hotel_booking_request.tariff_id')
                                                    ->where('hotel_booking_request.id', $bookingId[0])->first(['room_pricing.tariff_title']);

            $no_of_persons  =   (json_decode($booking->params))->no_of_persons;

            $template   =   'email.default';
            $subject    =   trans('email.edit_booking_subject', [ 'item_type' => $request->get('item_type')]);
            $message    =   trans('email.edit_hotel_booking_message',[
                'user'          =>  decrypt(Auth::user()->email),
                'no_of_persons' =>  $no_of_persons,
                'room_tariff'   =>  $hotelInfo->tariff_title
            ]);

            $mail_helper    =   new SendMailHelper(env('MAIL_FROM_ADDRESS'), $merchantInfo->email, $template, $subject, $message);
            $mail_helper->sendMail();

            $request->session()->flash('status', 'Your room edit request has been sent to '.$merchantInfo->first_name. ' '.$merchantInfo->last_name.'.');
        }

        return redirect()->route('scubaya::user::bookings::my_bookings', [Auth::id()]);
    }

    public function invoice(Request $request)
    {
        $invoice    =   Invoices::find($request->id);

        if($request->isMethod('post')){
            $pdf = PDF::loadView('user.invoice.pdf', ['invoice'  =>  $invoice,'ip'   =>  $request->ip()]);
            return $pdf->download('invoice'.$invoice->invoice_id.'.pdf');
        }

        return view('user.invoice.index',['invoice'  =>  $invoice,'ip'   =>  $request->ip()]);
    }
}
