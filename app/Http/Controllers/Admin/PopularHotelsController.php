<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\Hotel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PopularHotelsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /*$hotels   =   Hotel::join('website_details', 'website_details.website_id', '=', 'hotels_general_information.id')
                            ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                            ->where('doc.status', MERCHANT_STATUS_APPROVED)
                            ->where('website_details.website_type', HOTEL)
                            ->select('hotels_general_information.*')
                            ->get();*/

        $hotels =   Hotel::all();

        return view('admin.manage.popular_hotels.index')->with('hotels', $hotels)->with('sno', 1);
    }

    public function isHotelPopular(Request $request)
    {
        $hotel  =   Hotel::find($request->hotel_id);

        $hotel->is_hotel_popular    =   $request->is_popular;
        $hotel->update();
    }
}
