<?php

namespace App\Http\Controllers\Front;

use App\Elasticsearch\Destinations\DestinationRepository;
use App\Elasticsearch\DiveCenters\DiveCenterRepository;
use App\Scubaya\model\Destinations;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\ProductCheckouts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Subscriptions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscribeScubaya;
use App\Elasticsearch\Hotels\HotelsRepository;
use App\Scubaya\model\HomePageContent;
use Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if($request->hasCookie('scubaya_dive_in') ){
            if($request->hasCookie('courses')){
                DiveCenterCheckout::transferCookieCartToDatabase();
            }
            if($request->hasCookie('products')){
                ProductCheckouts::transferCookieCartToDatabase();
            }
        }

        if(isset($_GET['error'])){
            $request->session()->put('error',$_GET['error']);
            return redirect(url()->current());
        }

        if($request->session()->has('error')){
            $_GET['error']      =   $request->session()->get('error');
            $_GET['show_popup'] =   true;
            $request->session()->forget('error');
        }

        $destinations   =   Destinations::where('is_destination_popular', 1)->get();

        /*$diveCenters    =   ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                            ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                            ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                            ->where('website_details.website_type', DIVE_CENTER);*/

        $diveCenters    =   ManageDiveCenter::where('status', PUBLISHED);

        /*$hotels         =   Hotel::join('website_details', 'website_details.website_id', '=', 'hotels_general_information.id')
                                    ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                    ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                    ->where('website_details.website_type', HOTEL);*/

        $hotels         =   Hotel::where('status', PUBLISHED);

        return view('front.home.index')
            ->with('homepageContent',HomePageContent::first())
            ->with('destinations',$destinations)
            ->with('merchantSubAccountInfo', $this->_getMerchantSubAccountsInfo($diveCenters, $hotels))
            ->with('hotels',$hotels->where('hotels_general_information.is_hotel_popular', 1)->get(['hotels_general_information.*']))
            ->with('dive_centers',$diveCenters->where('manage_dive_centers.is_center_popular', 1)->get(['manage_dive_centers.*']));
    }

    protected function _getMerchantSubAccountsInfo($centers, $hotels)
    {
        $diveCenters    =   $centers->get(['manage_dive_centers.*']);

        $hotels         =   $hotels->get(['hotels_general_information.*']);

        $centers        =   array();
        $i              =   0;

        foreach ($diveCenters as $center){
            $x         =   array();

            $mimeType  =   explode('.', $center->image);

            $x[]  = $center->id;
            $x[]  = $center->name;
            $x[]  = strtoupper($mimeType[1]);
            //$x[]  = @base64_encode(file_get_contents(asset('assets/images/scubaya/dive_center/'.$center->merchant_key.'/'.$center->id.'-'.$center->image)));
            $x[]  = $center->latitude;
            $x[]  = $center->longitude;
            $x[]  = $center->address;

            $centers[$i]  =   $x;
            $i++;
        }

        $Hotels    =   array();
        $i         =   0;

        foreach ($hotels as $hotel){
            $x  =   array();

            $mimeType  =   explode('.', $hotel->image);

            $x[]  = $hotel->id;
            $x[]  = $hotel->name;
            $x[]  = strtoupper($mimeType[1]);
            //$x[]  = @base64_encode(file_get_contents(asset('assets/images/scubaya/hotel/'.$hotel->merchant_primary_id.'/'.$hotel->id.'-'.$hotel->image)));
            $x[]  = $hotel->latitude;
            $x[]  = $hotel->longitude;
            $x[]  = $hotel->address;

            $Hotels[$i]  =   $x;
            $i++;
        }

        return [
            'hotels'    =>  $Hotels,
            'centers'   =>  $centers
        ];
    }

    /*subscription for scubaya*/
    public function subscribe(Request $request)
    {
        /*validation*/
        $validator = Validator::make($request->all(), [
            'email'         => 'email|unique:subscription'
        ]);

        /*if validation passes*/
        if ($validator->passes()) {
            $data['email']          = Input::get('email');

            $subscribe_scubaya      = new SubscribeScubaya($data['email']);

            /*subscription*/
            Subscriptions::subscribe($data);

            /*mail after subscription*/
            Mail::to('bubbles@scubaya.com')->send($subscribe_scubaya);

            return response()->json(['status' => 'Your subscription is successful']);
        }
        /*if validation fails*/
        return response()->json(['errors' => $validator->errors()]);
    }

    public function toc()
    {
        return view('front.pages.toc');
    }

    public function aboutUs()
    {
        return view('front.pages.about_us');
    }

    public function searchAll(HotelsRepository $hotelsRepository, DiveCenterRepository $diveCenterRepository, DestinationRepository $destinationRepository)
    {
        $search_query   =   Input::get('query');

        if( Input::get('filter') == DIVE_CENTER ) {
            $results    =   $diveCenterRepository->search($search_query)->toJson();
        }

        else if( Input::get('filter') == HOTEL ) {
            $results    =   $hotelsRepository->search($search_query)->toJson();
        }

        else {
            $results    =   $destinationRepository->search($search_query)->toJson();
        }

        return response()->json(["results"  =>  $results ? $results : '{}']);
    }
}
