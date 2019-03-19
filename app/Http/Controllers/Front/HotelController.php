<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Scubaya\model\Cart;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\HotelCheckout;
use App\Scubaya\model\ProductCheckouts;
use App\Scubaya\model\RoomPricing;
use App\Scubaya\model\Rooms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\Cast\Object_;

class HotelController extends Controller
{
    public function __construct(Request $request)
    {
        if($request->hasCookie('scubaya_dive_in') ){
            if($request->hasCookie('courses')){
                DiveCenterCheckout::transferCookieCartToDatabase();
            }
            if($request->hasCookie('products')){
                ProductCheckouts::transferCookieCartToDatabase();
            }
        }
    }

    public function allHotels(Request $request)
    {
        if(isset($_GET['error'])){
            $request->session()->put('error',$_GET['error']);
            return redirect(url()->current());
        }

        if($request->session()->has('error')){
            $_GET['error']      =   $request->session()->get('error');
            $_GET['show_popup'] =   true;
            $request->session()->forget('error');
        }

        /*$query  =   Hotel::join('website_details', 'hotels_general_information.id', '=', 'website_details.website_id')
                         ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                         ->where('doc.status','=', MERCHANT_STATUS_APPROVED)
                         ->where('website_details.website_type', '=', HOTEL);*/


        $query  =   Hotel::where('status', PUBLISHED);

        if($request->checkin && $request->checkout && $request->guests) {

            /* check if room is available for the selected number of guests */
            $areRoomsAvailable   =   $query->join('room_details', 'hotels_general_information.id', 'room_details.hotel_id')
                                           ->where('room_details.max_people', '<=', $request->guests)
                                           ->count();

            if($areRoomsAvailable) {
                $hotelInfo      =   $query->select('hotels_general_information.*')->groupBy('room_details.hotel_id')->get();
            } else {
                $hotelInfo      =   [];
            }

        } else {
            $areRoomsAvailable   =   $query->join('room_details', 'hotels_general_information.id', 'room_details.hotel_id')
                ->where('room_details.max_people', '<=', 1)
                ->count();

            if($areRoomsAvailable) {
                $hotelInfo      =   $query->select('hotels_general_information.*')->groupBy('room_details.hotel_id')->get();
            } else {
                $hotelInfo      =   [];
            }
        }

        $merchantPrimaryIds   =   Hotel::select('merchant_primary_id')->groupBy('merchant_primary_id')->get();
        $exchangeRateHelper   =   new ExchangeRateHelper($request->ip(), $merchantPrimaryIds);

        $exchangeRate         =   $exchangeRateHelper->getExchangeRate();
        $request->session()->put('exchange-rate',  $exchangeRate);

        return view('front.home.hotels')
            ->with('hotelInfo', count($hotelInfo)  ? $this->_formatHotelData($hotelInfo) : '[]')
            ->with('minPrices', json_encode($this->_getMinPrice($hotelInfo)))
            ->with('exchangeRate', json_encode($exchangeRate))
            ->with('checkin', $request->checkin)
            ->with('checkout', $request->checkout)
            ->with('guests', $request->guests);
    }

    public function showHotelDetails(Request $request)
    {
        $hotelInfo      =   Hotel::where('id', $request->hotel_id)->first();

        if($request->guests) {

            $roomDetails    =   Rooms::where('merchant_primary_id', $hotelInfo->merchant_primary_id)
                ->where('hotel_id', $request->hotel_id)
                ->where('max_people', '<=', $request->get('guests'))
                ->get();

        } else {
            $roomDetails    =   Rooms::where('merchant_primary_id', $hotelInfo->merchant_primary_id)
                ->where('hotel_id', $request->hotel_id)->get();
        }

        return view('front.home.hotel_details')
            ->with('hotelInfo', $hotelInfo)
            ->with('roomDetails', $roomDetails)
            ->with('exchangeRate', $request->session()->get('exchange-rate'));
    }

    public function _formatHotelData($data)
    {
        $result         = array();
        $final_result   = array();

        foreach($data as  $info){
            $result['id']                   =   $info['id'];
            $result['name']                 =   $info['name'];
            $result['state']                =   $info['state'];
            $result['country']              =   $info['country'];
            $result['merchant_primary_id']  =   $info['merchant_primary_id'];
            $result['image']                =   $info['image'];
            $result['location_address']     =   $info['address'];

            array_push($final_result,$result);
        }

        return html_entity_decode(json_encode($final_result));
    }

    protected function _getMinPrice($hotelInfo)
    {
        $i  =   0;
        $j  =   0;
        $pricePerNightForMicro  =   array();
        $prices                 =   array();
        $minPrice               =   array();
        $minPrices              =   array();

        if(count($hotelInfo)) {
            foreach ($hotelInfo as $info) {
                $rooms = Rooms::where('merchant_primary_id', $info->merchant_primary_id)
                    ->where('hotel_id', $info->id)->get();

                if (count($rooms) > 0) {
                    foreach ($rooms as $room) {
                        $roomPricing = RoomPricing::where('room_id', $room->id)->get(['additional_tariff_data']);

                        if (count($roomPricing) > 0) {
                            foreach ($roomPricing as $pricing) {
                                $pricing = (array)json_decode($pricing->additional_tariff_data);

                                if (key($pricing) == 'micro') {
                                    $pricePerNightForMicro = (array)json_decode($pricing['micro']->price_per_night_manually);
                                    $todayEpoch = $this->_getRequestTime();

                                    foreach ($pricePerNightForMicro as $key => $value) {
                                        if ($key == $todayEpoch) {
                                            $prices[$i] = $value;
                                        }
                                    }
                                }

                                if (key($pricing) == 'normal') {
                                    $prices[$i] = $pricing['normal']->rate;
                                }

                                if (key($pricing) == 'advance') {
                                    $prices[$i] = $pricing['advance']->rate;
                                }

                                $i++;
                            }

                            $minPrice[$j] = min($prices);

                            $pricePerNightForMicro = array();
                            $prices = array();
                            $i = 0;
                            $j++;
                        }
                    }
                    $minPrices[$info->merchant_primary_id][$info->id] = count($minPrice) ? min($minPrice) : 0;
                }
                $j = 0;
            }
        }

        return $minPrices;
    }

    protected function _getRequestTime()
    {
        $today  = date('Y-m-d', $_SERVER['REQUEST_TIME']);
        $today  = explode('-', $today);

        /* mktime(hour, minute, second, month, day, year) */
        $epoch  = mktime(0, 0, 0, $today[1], $today[2], $today[0]);

        return $epoch;
    }

    public function updateNoOfPersonsForBooking(Request $request)
    {
        $is_user_logged_in     =   $request->hasCookie('scubaya_dive_in');
        $tariff_id             =   Input::get('tariff_id');
        $persons               =   Input::get('persons');

        if($is_user_logged_in){
            $user_id            =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id], ['item_type', $request->type], ['item_id',$tariff_id],['status',CHECKOUT_PENDING]])
                ->update(['item_data->no_of_persons' => $persons]);

            return response()->json(true);
        }else{
            $hotel_cart                =   $request->hasCookie('hotel')? unserialize(Crypt::decrypt($_COOKIE['hotel'])) : [];
            $hotel_cart[$tariff_id]['no_of_persons']    =   $persons;

            return response()->json(true)->withCookie(Cookie::make('hotel', serialize($hotel_cart), 60*24, '/'));
        }
    }

    public function deleteHotelItem(Request $request)
    {
        if($request->hasCookie('scubaya_dive_in')){
            $user_id            =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id ], ['item_type', $request->type], ['item_id',$request->id]])->delete();
        }else{
            if($request->hasCookie('hotel')){
                $cart   =    unserialize(Crypt::decrypt($_COOKIE['hotel']));
                unset($cart[$request->id]);
                setcookie('hotel', Crypt::encrypt(serialize($cart)), time() + 86400 , '/');
            }
        }
        return redirect()->route('scubaya::checkout::cart');
    }
}
