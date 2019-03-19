<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Rooms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageBookingsController extends Controller
{
    private $authUserId ;

    private $noOfRoomsPerPage   =   15;

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

    public function index(Request $request)
    {
        $rooms  =   Rooms::where('merchant_primary_id', $this->authUserId)
                            ->where('hotel_id', $request->hotel_id)
                            ->get();

        return view('merchant.hotel.manage_bookings.index')
                ->with('rooms', $rooms)
                ->with('hotel_id', $request->hotel_id);
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'start_date'    =>  'required',
            'end_date'      =>  'required',
            'room'          =>  'required',
            'status'        =>  'required',
        ]);
    }
}
