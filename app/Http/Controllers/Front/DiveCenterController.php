<?php

namespace App\Http\Controllers\Front;

use App\Elasticsearch\DiveCenters\DiveCenterRepository;
use App\Elasticsearch\DiveCenters\ElasticSearchDiveCenterRepository;
use App\Events\UserContactRequest;
use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Destinations;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\DiveSite;
use App\Scubaya\model\EmailTemplate;
use App\Scubaya\model\Group;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\ProductCheckouts;
use App\Scubaya\model\Products;
use App\Scubaya\model\User;
use App\Scubaya\model\WebsiteDetails;
use Carbon\Carbon;
use Elasticsearch\Client;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Scubaya\Helpers\ExchangeRateHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Scubaya\model\DiveCenter;
use App\Scubaya\model\Boat;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\Affiliations;
use App\Scubaya\model\Courses;
use Illuminate\Support\Facades\View;

class DiveCenterController extends Controller
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

    public function DiveCenters(Request $request,DiveCenterRepository $dive_center_repository)
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

        /*$diveCenters          =   ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                                  ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                                  ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                                  ->where('website_details.website_type', DIVE_CENTER)
                                                  ->select('manage_dive_centers.*')
                                                  ->get();*/

        $diveCenters    =   ManageDiveCenter::where('status', PUBLISHED)->get();

        return view('front.home.dive_center.dive_centers')
            ->with(['diveCenters'   => json_encode($this->_formatDiveCentersData($diveCenters))]);
    }

    //when a dive center is selected, then control falls under this method
    public function diveCentersDetails(Request $request)
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

        $diveCenter         =  ManageDiveCenter::where('id', $request->center_id)->first();

        $courses            =  Courses::whereRaw('JSON_CONTAINS(dive_center, \'["'.$request->center_id.'"]\')')
                                    ->where('merchant_key', $diveCenter->merchant_key)
                                    ->get();

        /* TODO: needs to be changed */
        $exchangeRateHelper =  new ExchangeRateHelper($request->ip(), (array)$diveCenter->merchant_key);
        $exchangeRate       =  $exchangeRateHelper->getExchangeRate();
        $request->session()->put('exchange-rate',  $exchangeRate);

        $_GET['center_id']      =   $request->center_id;
        $_GET['center_name']    =   $request->center_name;

        /*$hotels = Hotel::join('website_details', 'hotels_general_information.id', '=', 'website_details.website_id')
                    ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                    ->where('doc.status', MERCHANT_STATUS_APPROVED)
                    ->where('website_details.website_type', '=', HOTEL)
                    ->select('hotels_general_information.*')
                    ->take(4)->get();*/

        $hotels =   Hotel::where('status', PUBLISHED)->take(4)->get();

        $language_spoken    =   [];
        $languages          =   json_decode($diveCenter->language_spoken);
        if($languages){
            foreach ($languages as $language){
                $dcLanguages    =   DB::table('languages')->select('country_code','name')->where('name',$language)->first();
                array_push($language_spoken,$dcLanguages);
            }
        }

        $consumerAddress    =   WebsiteDetails::where('merchant_key', $diveCenter->merchant_key)
                                ->where('website_id', $diveCenter->id)
                                ->where('website_type', DIVE_CENTER)
                                ->value('address');

        $showContactModule  =   MerchantDetails::where('merchant_primary_id', $diveCenter->merchant_key)
                                                ->value('contact_module');

        return view('front.home.dive_center.dive_centers_details')->with([
            'diveCentersObject'      =>  $diveCenter,
            'courses'                =>  $courses,
            'consumerAddress'        =>  $consumerAddress,
            'affiliations'           =>  json_decode($diveCenter->affiliations),
            'member_affiliations'    =>  json_decode($diveCenter->member_affiliations),
            'language'               =>  $language_spoken,
            'payment_methods'        =>  json_decode($diveCenter->payment_methods),
            'distanceToDecoChamber'  =>  $this->getNearestDecoChamber($diveCenter),
            'diveSites'              =>  $this->getNearestDiveSites($diveCenter),
            'destinations'           =>  !empty($destinations) ? $destinations : null,
            'hotel_recommendations'  =>  $hotels,
            'instructorInfo'         =>  $this->_instructorInfo($diveCenter),
            'showContactModule'      =>  $showContactModule,
            'exchangeRate'           =>  $request->session()->get('exchange-rate')
        ]);
    }

    /**
     * to check the instructor is also a dive master
     * and count no of dive guides of dive center
     * @param $diveCenter, a dive center
     * @return array
     */
    protected function _instructorInfo($diveCenter)
    {
        $instructorDiveMaster   =   array();
        $diveGuides             =   array();

        $memberInfo             =   MerchantUsersRoles::where('merchant_id', $diveCenter->merchant_key)
                                                        ->whereRaw('JSON_CONTAINS( sub_account_rights, \'['.$diveCenter->id.']\' ,\'$.centers\' )')
                                                        //->whereRaw('JSON_CONTAINS(sub_account_rights->"$.centers", \'['.$diveCenter->id.']\')')
                                                        ->get();

        if($memberInfo) {
            foreach ($memberInfo as $info) {
                if(isset($info->group_id)) {
                    $groups =   json_decode($info->group_id);

                    foreach ($groups as $roleId => $value) {
                        if($roleId == Group::getRoleIdOfGroupMember('instructor')) {
                            if(isset($value->extra_role)) {
                                if($key = array_search(DIVE_MASTER, $value->extra_role) !== false) {
                                    $index                  =   array_search(DIVE_MASTER, $value->extra_role);
                                    $instructorDiveMaster[] =   $value->extra_role[$index];
                                }

                                if($key = array_search(DIVE_GUIDE, $value->extra_role) !== false) {
                                    $index        =   array_search(DIVE_GUIDE, $value->extra_role);
                                    $diveGuides[] =   $value->extra_role[$index];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data   =   [
            'noOfDiveGuides'            =>  count($diveGuides),
            'isInstructorDiveMaster'    =>  in_array(DIVE_MASTER, $instructorDiveMaster)
        ];
    }

    /**
     * get nearest decompression chamber from dive center
     *
     * @param $diveCenter
     * @return mixed
     */
    public function getNearestDecoChamber($diveCenter)
    {
        $distance       =   array();

        $destinations   =   Destinations::where('latitude', $diveCenter->latitude)
            ->where('longitude', $diveCenter->longitude)
            ->where('active', 1)
            ->get(['map_decompression_chambers']);

        if(!$destinations->isEmpty()) {
            foreach ($destinations as $destination) {
                $decoChamber    =   json_decode($destination->map_decompression_chambers);

                foreach ($decoChamber as $chamber => $info) {
                    $distance[]       =   $this->distance($diveCenter->latitude, $diveCenter->longitude, $info->lat, $info->long);
                }
            }
        }

        return count($distance) ? min($distance) : 0;
    }

    public function getNearestDiveSites($diveCenter)
    {
        $diveSitesWithinRadius  =   array();
        $diveSitesNotInRadius   =   array();

        $diveSites              =   DiveSite::where('is_active', 1)->get();

        if($diveSites->isNotEmpty()) {
            $index  =   0;

            foreach ($diveSites as $diveSite) {
                $distance   =   $this->distance(
                    $diveCenter->latitude,
                    $diveCenter->longitude,
                    (double)$diveSite->latitude,
                    (double)$diveSite->longitude
                );

                // prepare array with dive sites which is in radius of 20m from dive center
                if($distance <= 20){
                    $diveSitesWithinRadius[$index]['name']    =   $diveSite->name;
                    $diveSitesWithinRadius[$index]['key']     =   $diveSite->id;
                    $diveSitesWithinRadius[$index]['lat']     =   $diveSite->latitude;
                    $diveSitesWithinRadius[$index]['long']    =   $diveSite->longitude;
                    $diveSitesWithinRadius[$index]['image']   =   $diveSite->image;
                }

                // prepare array with all dive sites
                $diveSitesNotInRadius[$index]['name']     =   $diveSite->name;
                $diveSitesNotInRadius[$index]['key']      =   $diveSite->id;
                $diveSitesNotInRadius[$index]['lat']      =   $diveSite->latitude;
                $diveSitesNotInRadius[$index]['long']     =   $diveSite->longitude;
                $diveSitesNotInRadius[$index]['image']    =   $diveSite->image;

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

    public function coursesCheckout(Request $request)
    {
        if(isset($_GET['error'])){
            $request->session()->put('error',$_GET['error']);
            return redirect(url()->current());
        }

        /*return user to the dive center page if there is no exchange rates in the session*/
        if(!$request->session()->get('exchange-rate')){
            return redirect()->route('scubaya::diveCenters');
        }

        if(!$request->hasCookie('scubaya_dive_in')){
            $_GET['error']      =   "You have to login or Sign up first!";
            $_GET['show_popup'] =   true;
        }

        if($request->session()->has('error')){
            $_GET['error']      =   $request->session()->get('error');
            $_GET['show_popup'] =   true;
            $request->session()->forget('error');
        }

        $courses   = Courses::where('id', $request->course_id)->first(['products','image', 'course_name', 'course_pricing','image','merchant_key','dive_center_id']);

        $products  =  json_decode($courses->products);

        $included   = [];
        $excluded   = [];
        if($products) {
            foreach ($products as $key => $product) {
                if ($product->required) {
                    if ($product->IE) {
                        array_push($included, Products::where('id', $key)->first());
                    } else array_push($excluded, Products::where('id', $key)->first());
                }
            }
        }

        return view('front.home.dive_center.checkout_page')
            ->with([
                'courseId'          =>  $request->course_id,
                'courses'           =>  $courses,
                'merchant_key'      =>  $courses->merchant_key,
                'dive_center_id'    =>  $courses->dive_center_id,
                'products'          =>  $products,
                'included'          =>  $included,
                'excluded'          =>  $excluded,
                'exchangeRate'      =>  $request->session()->get('exchange-rate')
            ]);
    }

    public function _formatDiveCentersData($data)
    {
        $result         = array();
        $final_result   = array();

        foreach($data as  $info){
            $result['id']                   =   $info['id'];
            $result['merchant_key']         =   $info['merchant_key'];
            $result['name']                 =   $info['name'];
            $result['image']                =   $info['image'];
            $result['city']                 =   $info['city'];
            $result['state']                =   $info['state'];
            $result['country']              =   $info['country'];
            $result['location_address']     =   $info['address'];

            array_push($final_result,$result);
        }

        return $final_result;
    }

    public function CourseDetails(Request $request)
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

        $course    =   Courses::find($request->course_id);

        return view('front.home.dive_center.courses.index')
            ->with('course', $course)
            ->with('exchangeRate', $request->session()->get('exchange-rate'))
            ->with('courses', $this->_getAllCourses($request->center_id));
    }

    protected function _getAllCourses($centerId)
    {
        $courses        =   Courses::whereRaw('JSON_CONTAINS(dive_center, \'["'.$centerId.'"]\')')->get();

        $Courses        =   array();
        $i              =   0;

        if($courses) {
            foreach ($courses as $course){
                $x  =   array();

                $mimeType   =   $course->image ? explode('.', $course->image) : 'png';
                $location   =   json_decode($course->location);

                $x[]  = $centerId;
                $x[]  = $course->id;
                $x[]  = ManageDiveCenter::where('id', $centerId)->value('name');
                $x[]  = $course->course_name;
                $x[]  = is_array($mimeType) ? strtoupper($mimeType[1]) : $mimeType;
                $x[]  = $course->image
                        ? base64_encode(file_get_contents(asset('assets/images/scubaya/shop/courses/'.$course->merchant_key.'/'.$course->id.'-'.$course->image)))
                        : base64_encode(file_get_contents(asset('assets/images/default.png')));
                $x[]  = $location->lat;
                $x[]  = $location->long;
                $x[]  = $location->address;

                $Courses[$i]  =   $x;
                $i++;
            }
        }

        return $Courses;
    }

    public function checkProductAvailability(Request $request)
    {
        $productsAvailable  =   array();

        $checkIn            =   Carbon::createFromFormat('M d, Y', $request->check_in)->format('m-d-Y');
        $checkout           =   Carbon::createFromFormat('M d, Y', $request->check_out)->format('m-d-Y');

        $productsAvailable['courses']   =   $this->_getAvailableCourses($checkIn, $checkout, $request->dcId);

        return $productsAvailable;
    }

    protected function _getAvailableCourses($checkIn, $checkOut, $diveCenterId)
    {
        $coursesAvailable       =   array();
        $index                  =   0;

        $courses                =   Courses::where('course_start_date', '>=', $checkIn)
                                            ->where('course_end_date', '<=', $checkOut)
                                            ->whereRaw('JSON_CONTAINS(dive_center, \'["'.$diveCenterId.'"]\')')
                                            ->orderBy('course_start_date', 'asc')
                                            ->get();

        if($courses->isEmpty()) {
            $courses   =   Courses::whereRaw('JSON_CONTAINS(dive_center, \'["'.$diveCenterId.'"]\')')
                                    ->orderBy('course_start_date', 'asc')
                                    ->get();
        }

        foreach ($courses as $course) {
            $coursesAvailable['courses'][$index]['cname']      =   ucwords($course->course_name);
            $coursesAvailable['courses'][$index]['cid']        =   $course->id;
            $coursesAvailable['courses'][$index]['key']        =   $course->merchant_key;
            $coursesAvailable['courses'][$index]['image']      =   $course->image
                                                                   ? asset('assets/images/scubaya/shop/courses/'.$course->merchant_key.'/'.$course->id.'-'.$course->image)
                                                                   : asset('assets/images/default.png');
            $coursesAvailable['courses'][$index]['start_date'] =   date(" j M y", strtotime( str_replace('-', '/', $course->course_start_date) ) );
            $coursesAvailable['courses'][$index]['end_date']   =   date(  "j M y", strtotime( str_replace('-', '/', $course->course_end_date) ) );
            $coursesAvailable['courses'][$index]['price']      =   (json_decode($course->course_pricing))->price;

            $index++;
        }

        return $coursesAvailable;
    }

    public function getDiveSiteById(Request $request)
    {
        return DiveSite::find($request->key)->toJson();
    }

    /**
     * Send user query to the requested merchant
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendUserQuery(Request $request)
    {
        try{
            if($email = EmailTemplate::getTemplateByAction('merchant', 'user_query')) {
                $merchantKey                =   (new Hashids())->decode($request->get('key'));

                $data                       =   new \stdClass();
                $data->email                =   User::where('id', $merchantKey)->value('email');
                $data->merchant_login_url   =   route('scubaya::merchant::login');
                $email->setData($data);

                (new SendMailHelper($email))->send();

                // Fire an event to log contact request and its notification
                //event(new UserContactRequest($merchantKey, $request->get('email'), $request->get('query')));

                $response['success']    =   'Your request has been sent successfully.';
            }
        } catch (\Exception $e) {
            $response['error']      =   'something went wrong!!';
        }

        return response()->json([ 'message' => $response]);
    }
}
