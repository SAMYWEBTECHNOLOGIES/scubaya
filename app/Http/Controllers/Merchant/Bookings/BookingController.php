<?php

namespace App\Http\Controllers\Merchant\Bookings;

use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Cart;
use App\Scubaya\model\Config;
use App\Scubaya\model\CourseBookingRequest;
use App\Scubaya\model\Courses;
use App\Scubaya\model\EditBooking;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\HotelBookingRequest;
use App\Scubaya\model\Invoices;
use App\Scubaya\model\MerchantDiveCenterInvoicesMapper;
use App\Scubaya\model\MerchantProductInvoicesMapper;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Orders;
use App\Scubaya\model\ProductBookingRequest;
use App\Scubaya\model\Products;
use App\Scubaya\model\RoomPricing;
use App\Scubaya\model\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BookingController extends Controller
{
    private $authUserId ;

    protected $paginateNo  =   10;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });
    }

    public function allBookings(Request $request)
    {

//        $per_page       =   10;
//        $page           =   Input::get('page',1);
//        /*Manually creating the pagination*/
//        $data           =   new LengthAwarePaginator($courses_bookings->forPage($page,$per_page),$courses_bookings->count(),$per_page);
//        /*Set the link on the */
//        $data           =   $data->setPath(url()->current());
//
//        $merchant_id    =   new \stdClass();
//        $merchant_id->merchant_primary_id   =   Auth::id();

        $exchangeRate       =   new ExchangeRateHelper($request->ip(),[$this->authUserId]);

        $courseBookings     =   CourseBookingRequest::where('merchant_key', $this->authUserId)->orderByDesc('created_at')->get();
        $productBookings    =   ProductBookingRequest::where('merchant_key', $this->authUserId)->orderByDesc('created_at')->get();
        $hotelBookings      =   HotelBookingRequest::where('merchant_key', $this->authUserId)->orderByDesc('created_at')->get();

        return view('merchant.bookings.all_bookings')
                    ->with('courseBookings',$courseBookings)
                    ->with('productBookings',$productBookings)
                    ->with('hotelBookings',$hotelBookings)
                    ->with('authId', $this->authUserId)
                    ->with('exchangeRate',$exchangeRate->getExchangeRate());
    }

    protected function _prepareInvoiceData($itemType, $cartId, $bookingId)
    {
        $invoice    =   new \stdClass();

        $invoice->invoice_no    =   Invoices::generateInvoiceNumber();
        $invoice->merchant_key  =   $this->authUserId;
        $invoice->order_id      =   $this->_getOrderId($cartId);
        $invoice->booking_id    =   json_encode([
            $itemType   =>  (array)$bookingId
        ]);

        return $invoice;
    }

    public function updateCourseBookingStatus(Request $request)
    {
        $cartId         =   (new Hashids())->decode($request->get('id'));

        $bookingRequest =   CourseBookingRequest::updateBookingRequestStatus($cartId, $request->get('status'));

        // only generate invoice when request is confirmed by merchant
        if( $request->get('status') == CONFIRMED_BOOKING_REQUEST) {
            Invoices::saveInvoice($this->_prepareInvoiceData('course', $cartId[0], $bookingRequest->id));
        }
    }

    public function updateProductBookingStatus(Request $request)
    {
        $cartId =   (new Hashids())->decode($request->get('id'));

        $bookingRequest =   ProductBookingRequest::updateBookingRequestStatus($cartId, $request->get('status'));

        // only generate invoice when request is confirmed by merchant
        if( $request->get('status') == CONFIRMED_BOOKING_REQUEST) {
            Invoices::saveInvoice($this->_prepareInvoiceData('product', $cartId[0], $bookingRequest->id));
        }
    }

    public function updateHotelBookingStatus(Request $request)
    {
        $cartId         =   (new Hashids())->decode($request->get('id'));

        $bookingRequest =   HotelBookingRequest::updateBookingRequestStatus($cartId, $request->get('status'));

        // only generate invoice when request is confirmed by merchant
        if( $request->get('status') == CONFIRMED_BOOKING_REQUEST) {
            Invoices::saveInvoice($this->_prepareInvoiceData('hotel', $cartId[0], $bookingRequest->id));
        }
    }

    protected function _getOrderId($cartId)
    {
        $orderId    =   0;

        $userId     =   Cart::where('id', $cartId)->value('user_key');

        $orders     =   Orders::where('user_key', $userId)->get();

        foreach ($orders as $order) {
            $orderedItems  =    (array)json_decode($order->items);

            if(in_array($cartId, $orderedItems)) {
                $orderId    =   $order->id;
            }
        }

        return $orderId;
    }

    /*  starting of functions to confirm course, product and hotel edit request
     *  and send confirmation email & notification to user
     */
    public function confirmCourseBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $courseInfo         =   Courses::join('course_booking_request', 'courses.id', '=', 'course_booking_request.course_id')
                                        ->where('course_booking_request.id', $editBookingData->booking_id)
                                        ->first();

        CourseBookingRequest::where('id', $editBookingData->booking_id)
                            ->update([
                                'no_of_people' =>  (json_decode($editBookingData->params)->no_of_persons),
                                'total'        =>  ((json_decode($editBookingData->params)->no_of_persons) * (json_decode($courseInfo->course_pricing))->price),
                                'status'       =>  NEW_BOOKING_REQUEST
                            ]);

        $this->_sendEmailToUserToConfirmCourseBooking($editBookingData, $courseInfo);

        // update edit booking request status to confirmed
        $editBookingData->status    =   CONFIRMED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for updated course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserToConfirmCourseBooking($editBookingData, $courseInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        $userKey   =   EditBooking::join('course_booking_request', 'course_booking_request.id', '=', 'edit_booking.booking_id')
                                    ->join('cart', 'cart.id', '=', 'course_booking_request.cart_id')
                                    ->where('edit_booking.id', $editBookingData->id)
                                    ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'course']);
        $message    =   trans('email.update_course_booking_request_message',[
            'no_of_persons' =>  (json_decode($editBookingData->params)->no_of_persons),
            'course'        =>  $courseInfo->course_name
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    public function confirmProductBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $productInfo        =   Products::join('product_booking_request', 'product_booking_request.product_id', '=', 'products.id')
                                        ->where('product_booking_request.id', $editBookingData->booking_id)
                                        ->first();

        ProductBookingRequest::where('id', $editBookingData->booking_id)
                            ->update([
                                'quantity' =>  (json_decode($editBookingData->params)->quantity),
                                'total'    =>  (json_decode($editBookingData->params)->quantity) * ($productInfo->price + ($productInfo->price * $productInfo->tax / 100)),
                                'status'   =>  NEW_BOOKING_REQUEST
                            ]);

        $this->_sendEmailToUserToConfirmProductBooking($editBookingData, $productInfo);

        // update edit booking request status to confirmed
        $editBookingData->status    =   CONFIRMED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for updated course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserToConfirmProductBooking($editBookingData, $productInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        // to get user email to send email
        $userKey   =   EditBooking::join('product_booking_request', 'edit_booking.booking_id', '=', 'product_booking_request.id')
                                   ->join('cart', 'cart.id', '=', 'product_booking_request.cart_id')
                                   ->where('edit_booking.id', $editBookingData->id)
                                   ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'product']);
        $message    =   trans('email.update_product_booking_request_message',[
            'quantity' =>  (json_decode($editBookingData->params)->quantity),
            'product'  =>  $productInfo->title
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    public function confirmHotelBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $hotelInfo          =   RoomPricing::join('hotel_booking_request', 'hotel_booking_request.tariff_id', '=', 'room_pricing.id')
                                            ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                            ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                            ->where('hotel_booking_request.id', $editBookingData->booking_id)
                                            ->first(['room_pricing.tariff_title', 'hotel.name']);

        HotelBookingRequest::where('id', $editBookingData->booking_id)
                            ->update([
                                'no_of_persons'     =>  (json_decode($editBookingData->params)->no_of_persons),
                                'total'             =>  $request->total_price,
                                'status'            =>  NEW_BOOKING_REQUEST
                            ]);

        $this->_sendEmailToUserToConfirmHotelBooking($editBookingData, $hotelInfo);

        // update edit booking request status to confirmed
        $editBookingData->status    =   CONFIRMED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for updated course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserToConfirmHotelBooking($editBookingData, $hotelInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        // to get user email to send email
        $userKey   =   EditBooking::join('hotel_booking_request', 'edit_booking.booking_id', '=', 'hotel_booking_request.id')
                                ->join('cart', 'cart.id', '=', 'hotel_booking_request.cart_id')
                                ->where('edit_booking.id', $editBookingData->id)
                                ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'hotel']);
        $message    =   trans('email.update_hotel_booking_request_message',[
            'no_of_persons' =>  (json_decode($editBookingData->params)->no_of_persons),
            'room_tariff'   =>  $hotelInfo->tariff_title,
            'hotel'         =>  $hotelInfo->name
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    /*  starting of functions to decline course, product and hotel edit request
     *  and send decline email & notification to user
     */
    public function declineCourseBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $courseInfo         =   Courses::join('course_booking_request', 'courses.id', '=', 'course_booking_request.course_id')
                                        ->where('course_booking_request.id', $editBookingData->booking_id)
                                        ->first();

        $this->_sendEmailToUserForDeclineCourseBooking($editBookingData, $courseInfo);

        // update edit booking request status to declined
        $editBookingData->status    =   DECLINED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for updated course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserForDeclineCourseBooking($editBookingData, $courseInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        $userKey   =   EditBooking::join('course_booking_request', 'course_booking_request.id', '=', 'edit_booking.booking_id')
                                ->join('cart', 'cart.id', '=', 'course_booking_request.cart_id')
                                ->where('edit_booking.id', $editBookingData->id)
                                ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.decline_booking_subject', [ 'item_type' => 'course']);
        $message    =   trans('email.decline_course_booking_message',[
            'no_of_persons' =>  (json_decode($editBookingData->params)->no_of_persons),
            'course'        =>  $courseInfo->course_name
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    public function declineProductBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $productInfo        =   Products::join('product_booking_request', 'product_booking_request.product_id', '=', 'products.id')
                                        ->where('product_booking_request.id', $editBookingData->booking_id)
                                        ->first();

        $this->_sendEmailToUserForDeclineProductBooking($editBookingData, $productInfo);

        // update edit booking request status to declined
        $editBookingData->status    =   DECLINED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for updated course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserForDeclineProductBooking($editBookingData, $productInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        // to get user email to send email
        $userKey   =   EditBooking::join('product_booking_request', 'edit_booking.booking_id', '=', 'product_booking_request.id')
                                    ->join('cart', 'cart.id', '=', 'product_booking_request.cart_id')
                                    ->where('edit_booking.id', $editBookingData->id)
                                    ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.decline_booking_subject', [ 'item_type' => 'product']);
        $message    =   trans('email.decline_product_booking_message',[
            'quantity' =>  (json_decode($editBookingData->params)->quantity),
            'product'  =>  $productInfo->title
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    public function declineHotelBooking(Request $request)
    {
        $editBookingData    =   EditBooking::find($request->booking_id);

        $hotelInfo          =   RoomPricing::join('hotel_booking_request', 'hotel_booking_request.tariff_id', '=', 'room_pricing.id')
                                        ->join('room_details', 'room_details.id', 'room_pricing.room_id')
                                        ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                        ->where('hotel_booking_request.id', $editBookingData->booking_id)
                                        ->first(['room_pricing.tariff_title', 'hotel.name']);

        $this->_sendEmailToUserForDeclineHotelBooking($editBookingData, $hotelInfo);

        // update edit booking request status to declined
        $editBookingData->status    =   DECLINED_EDIT_BOOKING_REQUEST;
        $editBookingData->update();

        //TODO : send notification to user for decline hotel booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }

    protected function _sendEmailToUserForDeclineHotelBooking($editBookingData, $hotelInfo)
    {
        $sender    =   User::where('id', $this->authUserId)->first(['email']);

        // to get user email to send email
        $userKey   =   EditBooking::join('hotel_booking_request', 'edit_booking.booking_id', '=', 'hotel_booking_request.id')
                                ->join('cart', 'cart.id', '=', 'hotel_booking_request.cart_id')
                                ->where('edit_booking.id', $editBookingData->id)
                                ->select('cart.user_key')->first();

        $userInfo   =   User::where('id', $userKey->user_key)->first(['email']);

        $template   =   'email.default';
        $subject    =   trans('email.decline_booking_subject', [ 'item_type' => 'hotel']);
        $message    =   trans('email.decline_hotel_booking_message',[
            'no_of_persons' =>  (json_decode($editBookingData->params)->no_of_persons),
            'room_tariff'   =>  $hotelInfo->tariff_title,
            'hotel'         =>  $hotelInfo->name
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    /* To update booking of course and
     * will send an email & notification to user for updating
     * his/her booking
    */
    public function updateCourseBooking(Request $request)
    {
        $bookingId  =   (new Hashids())->decode($request->booking_id);

        $courseBooking  =   CourseBookingRequest::find($bookingId[0]);

        $courseBooking->no_of_people    =   $request->get('no_of_persons');
        $courseBooking->status          =   NEW_BOOKING_REQUEST;
        $courseBooking->update();

        $userInfo  =   $this->_sendEmailToUserForCourseUpdate($courseBooking);

        $request->session()->flash('status', ' Course booking update email has been sent to '.decrypt($userInfo->first_name). ' '. decrypt($userInfo->last_name).'.');

        //TODO : send notification to user for edit course booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }


    protected function _sendEmailToUserForCourseUpdate($courseBooking)
    {
        $sender     =   User::where('id', $this->authUserId)->first(['email']);

        $courseInfo =   Courses::where('id', $courseBooking->course_id)->first(['course_name']);

        $userInfo   =   Cart::join('users', 'users.id', '=', 'cart.user_key')
                            ->where('cart.id', $courseBooking->cart_id)
                            ->first(['email', 'first_name', 'last_name']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'course']);
        $message    =   trans('email.update_course_booking_message',[
            'course'        =>  $courseInfo->course_name,
            'no_of_persons' =>  $courseBooking->no_of_people
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();

        return $userInfo;
    }

    /* To update booking of product and
     * will send an email & notification to user for updating
     * his/her booking
    */
    public function updateProductBooking(Request $request)
    {
        $bookingId  =   (new Hashids())->decode($request->booking_id);

        $productBooking  =   ProductBookingRequest::find($bookingId[0]);

        $productBooking->quantity    =   $request->get('quantity');
        $productBooking->status      =   NEW_BOOKING_REQUEST;
        $productBooking->update();

        $userInfo  =   $this->_sendEmailToUserForProductUpdate($productBooking);

        $request->session()->flash('status', ' Product booking update email has been sent to '.decrypt($userInfo->first_name). ' '. decrypt($userInfo->last_name).'.');

        //TODO : send notification to user for edit product booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }


    protected function _sendEmailToUserForProductUpdate($productBooking)
    {
        $sender         =   User::where('id', $this->authUserId)->first(['email']);

        $productInfo    =   Products::where('id', $productBooking->product_id)->first(['title']);

        $userInfo       =   Cart::join('users', 'users.id', '=', 'cart.user_key')
                                ->where('cart.id', $productBooking->cart_id)
                                ->first(['email', 'first_name', 'last_name']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'product']);
        $message    =   trans('email.update_product_booking_message',[
            'product'  =>  $productInfo->title,
            'quantity' =>  $productBooking->quantity
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();

        return $userInfo;
    }

    /* To update booking of hotel room and
     * will send an email & notification to user for updating
     * his/her booking
    */
    public function updateHotelRoomBooking(Request $request)
    {
        $bookingId  =   (new Hashids())->decode($request->booking_id);

        $hotelBooking  =   HotelBookingRequest::find($bookingId[0]);

        $hotelBooking->no_of_persons    =   $request->get('no_of_persons');
        $hotelBooking->status           =   NEW_BOOKING_REQUEST;
        $hotelBooking->update();

        $userInfo  =   $this->_sendEmailToUserForHotelRoomUpdate($hotelBooking);

        $request->session()->flash('status', ' Hotel booking update email has been sent to '.decrypt($userInfo->first_name). ' '. decrypt($userInfo->last_name).'.');

        //TODO : send notification to user for edit hotel booking

        return Redirect::to(route('scubaya::merchant::bookings::all_bookings', [ $this->authUserId ]));
    }


    protected function _sendEmailToUserForHotelRoomUpdate($hotelBooking)
    {
        $sender         =   User::where('id', $this->authUserId)->first(['email']);

        $hotelInfo      =   RoomPricing::join('room_details', 'room_details.id', '=', 'room_pricing.room_id')
                                        ->join('hotels_general_information as hotel', 'hotel.id', 'room_details.hotel_id')
                                        ->where('room_pricing.id', $hotelBooking->tariff_id)
                                        ->first(['room_pricing.tariff_title', 'hotel.name']);

        $userInfo       =   Cart::join('users', 'users.id', '=', 'cart.user_key')
                                ->where('cart.id', $hotelBooking->cart_id)
                                ->first(['email', 'first_name', 'last_name']);

        $template   =   'email.default';
        $subject    =   trans('email.update_booking_subject', [ 'item_type' => 'hotel']);
        $message    =   trans('email.update_hotel_room_booking_message',[
            'room'     =>   $hotelInfo->tariff_title,
            'hotel'    =>   $hotelInfo->name,
            'persons'  =>   $hotelBooking->no_of_persons
        ]);

        $mail_helper    =   new SendMailHelper($sender->email, decrypt($userInfo->email), $template, $subject, $message);
        $mail_helper->sendMail();

        return $userInfo;
    }
}
