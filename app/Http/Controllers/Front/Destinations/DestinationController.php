<?php

namespace App\Http\Controllers\Front\Destinations;

use App\Scubaya\model\DiveSite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scubaya\model\Destinations;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\Hotel;
use Illuminate\Support\Facades\DB;
use App\Elasticsearch\DiveCenters\DiveCenterRepository;
use Illuminate\Support\Facades\Input;

class DestinationController extends Controller
{

    public function Destinations(Request $request)
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

        $destinations         =   Destinations::where('active',1)
                                             ->where('is_sub_destination',0)
                                             ->get();

        return view('front.home.destination.index', ['destinations' =>  $this->_formatDestinationData($destinations)]);

    }

    public function _formatDestinationData($data)
    {
        $result         = array();
        $final_result   = array();

        foreach($data as  $info) {
            $result['id']       =   $info['id'];
            $result['name']     =   $info['name'];
            $result['country']  =   $info['country'];
            $result['image']    =   $info['image'];
            $result['location'] =   $info['location'];

            array_push($final_result,$result);
        }

        return json_encode($final_result);
    }

    public function destinationDetails(Request $request)
    {
       $destinationInfo  = Destinations::where('id', $request->destination_id)->first();
       $subDestinations  = Destinations::where('is_subdestination_of',$request->destination_id)
                                        ->where('active',1)
                                        ->get();

       $languageSpoken    =   [];
       $languages         =   json_decode($destinationInfo->language_spoken);

        if($languages){
            foreach ($languages as $language){
                $dcLanguages    =   DB::table('languages')->select('country_code','name')->where('name',$language)->first();
                array_push($languageSpoken,$dcLanguages);
            }
        }

        $diveCenters = ManageDiveCenter::where('country',$destinationInfo->country)
                                        ->where('status', PUBLISHED)
                                        ->count();

        $hotels      = Hotel::where('country',$destinationInfo->country)
                            ->where('status', PUBLISHED)
                            ->count();

        return view('front.home.destination.details',[
            'destinationInfo'    => $destinationInfo,
            'subDestinations'    => $subDestinations,
            'languageSpoken'     => $languageSpoken,
            'diveCenters'        => $diveCenters,
            'hotels'             => $hotels,
            'diveSites'          => $this->getNearestDiveSites($destinationInfo)
        ]);
    }

    public function subDestinationDetails(Request $request)
    {
        $subDestinationInfo =   Destinations::where('id',$request->subdestination_id)->first();

        $language_spoken    =   [];
        $languages          =   json_decode($subDestinationInfo->language_spoken);

        if($languages){
            foreach ($languages as $language){
                $dcLanguages    =   DB::table('languages')->select('country_code','name')->where('name',$language)->first();
                array_push($language_spoken,$dcLanguages);
            }
        }

        $diveCenters = ManageDiveCenter::where('status', PUBLISHED)
                                        ->where('country',$subDestinationInfo->country)
                                        ->count();

        $hotels      = Hotel::where('status', PUBLISHED)
                            ->where('country',$subDestinationInfo->country)
                            ->count();

        return view('front.home.destination.sub_destination',[
            'subDestinationInfo'    => $subDestinationInfo,
            'languageSpoken'        => $language_spoken,
            'diveCenters'           => $diveCenters,
            'hotels'                => $hotels,
            'diveSites'             => $this->getNearestDiveSites($subDestinationInfo)
        ]);
    }

    /**
     * get nearest dive sites from destination
     * @param $destination
     * @return array
     */
    public function getNearestDiveSites($destination)
    {
        $diveSitesWithinRadius  =   array();
        $diveSitesNotInRadius   =   array();

        $diveSites              =   DiveSite::where('is_active', 1)->get();

        if($diveSites->isNotEmpty()) {
            $index  =   0;

            foreach ($diveSites as $diveSite) {
                $distance   =   $this->distance(
                    $destination->latitude,
                    $destination->longitude,
                    (double)$diveSite->latitude,
                    (double)$diveSite->longitude
                );

                if($distance <= 20){
                    $diveSitesWithinRadius[$index]['name']      =   $diveSite->name;
                    $diveSitesWithinRadius[$index]['key']       =   $diveSite->id;
                    $diveSitesWithinRadius[$index]['lat']       =   $diveSite->latitude;
                    $diveSitesWithinRadius[$index]['long']      =   $diveSite->longitude;
                    $diveSitesWithinRadius[$index]['image']     =   $diveSite->image;
                    $diveSitesWithinRadius[$index]['country']   =   $diveSite->country;
                } else {
                    $diveSitesNotInRadius[$index]['name']       =   $diveSite->name;
                    $diveSitesNotInRadius[$index]['key']        =   $diveSite->id;
                    $diveSitesNotInRadius[$index]['lat']        =   $diveSite->latitude;
                    $diveSitesNotInRadius[$index]['long']       =   $diveSite->longitude;
                    $diveSitesNotInRadius[$index]['image']      =   $diveSite->image;
                    $diveSitesNotInRadius[$index]['country']    =   $diveSite->country;
                }

                $index++;
            }
        }

        return [
            'diveSitesWithinRadius' =>  $diveSitesWithinRadius,
            'diveSitesNotInRadius'  =>  $diveSitesNotInRadius
        ];
    }

    /**
     * calculate the distance between two latitude longitude points
     * @param $latA
     * @param $lngA
     * @param $latB
     * @param $lngB
     * @return float|int
     */
    public function distance($latA, $lngA,$latB, $lngB) {
        // distance is zero because they're the same point
        if (($latA == $latB) && ($lngA == $lngB)) {
            return 0;
        }

        // Earth's average radius, in meters
        $R            = 6371000;

        $radiansLAT_A = deg2rad($latA);
        $radiansLAT_B = deg2rad($latB);
        $variationLAT = deg2rad($latB - $latA);
        $variationLNG = deg2rad($lngB - $lngA);

        $a = sin($variationLAT/2) * sin($variationLAT/2)
            + cos($radiansLAT_A) * cos($radiansLAT_B) * sin($variationLNG/2) * sin($variationLNG/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        $d = $R * $c;

        return $d / 1000;
    }
}
