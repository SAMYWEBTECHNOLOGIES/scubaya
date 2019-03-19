<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\ManageDiveCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PopularDiveCentersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /*$diveCenters          =   ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                                    ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                                    ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                                    ->where('website_details.website_type', DIVE_CENTER)
                                                    ->select('manage_dive_centers.*')
                                                    ->get();*/

        $diveCenters    =   ManageDiveCenter::where('status', PUBLISHED)->get();

        return view('admin.manage.popular_dive_centers.index')
            ->with('diveCenters', $diveCenters)
            ->with('sno', 1);
    }

    public function isCenterPopular(Request $request)
    {
        $diveCenter  =   ManageDiveCenter::find($request->center_id);

        $diveCenter->is_center_popular    =   $request->is_popular;
        $diveCenter->update();
    }
}
