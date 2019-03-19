<?php

namespace App\Http\Controllers\Merchant\DiveCenter;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Activity;
use App\Scubaya\model\Affiliations;
use App\Scubaya\model\Boat;
use App\Scubaya\model\Courses;
use App\Scubaya\model\DiveDayPlanning;
use App\Scubaya\model\Facility;
use App\Scubaya\model\Gear;
use App\Scubaya\model\Infrastructure;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\PaymentMethod;
use App\Scubaya\model\Speciality;
use App\Scubaya\model\User;
use App\Scubaya\model\WebsiteDetails;
use App\Scubaya\model\WebsiteDocumentsMapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class DiveCenterController extends Controller
{
    private $noOfDiveCentersPerPage   =   15;

    private $authUserId;

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

    public function index()
    {
        if(Auth::user()->is_merchant_user) {
            $centers      =   array();
            $centerRights =   json_decode(MerchantUsersRoles::where('user_id', Auth::id())->value('sub_account_rights'));

            if($centerRights) {
                foreach ($centerRights as $key => $value) {
                    if($key == 'centers') {
                        array_push($centers, $value);
                    }
                }
            }

            $diveCenters    =   ManageDiveCenter::whereIn('id', array_flatten($centers))->paginate($this->noOfDiveCentersPerPage);
        } else {
            $diveCenters    =   ManageDiveCenter::where('merchant_key',$this->authUserId)->paginate($this->noOfDiveCentersPerPage);
        }

        return view('merchant.dive_center.index',[
            'diveCenters'   =>  $diveCenters,
            'authId'        =>  $this->authUserId
        ]);
    }

    protected function _prepareData($request, $file,$galleryImageFiles)
    {
        $diveCenter  =   new \stdClass();

        $diveCenter->merchant_key =   $this->authUserId;
        $diveCenter->name         =   $request->get('name');

        // prepare season information for dive center
        $season     =   [];

        if($request->from) {
            for($i=1;$i<count($request->from)+1;$i++){
                $season['info'][]     =   [$request->season_feasibility[$i]=>[$request->from[$i],$request->till[$i]]];
            }
        }
        $season['whole_year']     =   $request->get('whole_year');

        if($file){
            $diveCenter->image    =   $file->getClientOriginalName();
        }

        if(count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $diveCenter->gallery  =   json_encode($images);
        }

        $diveCenter->address                =   $request->get('address');
        $diveCenter->city                   =   $request->get('city');
        $diveCenter->state                  =   $request->get('state');
        $diveCenter->country                =   $request->get('country');
        $diveCenter->zipcode                =   $request->get('zip_code');
        $diveCenter->latitude               =   $request->get('latitude');
        $diveCenter->longitude              =   $request->get('longitude');
        $diveCenter->facebook_url           =   $request->get('facebook_url');
        $diveCenter->twitter_url            =   $request->get('twitter_url');
        $diveCenter->instagram_url          =   $request->get('instagram_url');
        $diveCenter->activities             =   json_encode($request->get('activity'));
        $diveCenter->non_diving_activities  =   json_encode($request->get('non_diving_activity'));
        $diveCenter->facilities             =   json_encode($request->get('facility'));
        $diveCenter->specialities           =   json_encode($request->get('speciality'));
        $diveCenter->member_affiliations    =   json_encode($request->get('member_affiliations'));
        $diveCenter->affiliations           =   json_encode($request->get('affiliations'));
        $diveCenter->language_spoken        =   json_encode($request->get('language'));
        $diveCenter->infrastructure         =   json_encode($request->get('infrastructure'));
        $diveCenter->payment_methods        =   json_encode($request->get('payment_method'));
        $diveCenter->required_documents     =   $request->get('documents');
        $diveCenter->cancellation_policy    =   $request->get('cancellation_policy');
        $diveCenter->distance_from_sea      =   $request->get('distance_from_sea');
        $diveCenter->groups                 =   json_encode($request->get('group'));
        $diveCenter->opening_days           =   json_encode($request->get('opening_days'));
        $diveCenter->short_description      =   $request->get('dc_short_description');
        $diveCenter->long_description       =   $request->get('dc_long_description');
        $diveCenter->read_before_you_go     =   json_encode($request->get('read'));
        $diveCenter->gears                  =   json_encode($request->get('gear'));
        $diveCenter->filling_station        =   json_encode($request->get('filling_station'));
        $diveCenter->nitrox                 =   $request->get('nitrox');
        $diveCenter->discovery_dives        =   json_encode($request->get('discovery_dives'));
        $diveCenter->fun_dives              =   json_encode($request->get('fun_dives'));
        $diveCenter->other_dives            =   json_encode($request->get('other_dives'));
        $diveCenter->season                 =   json_encode($season);
        $diveCenter->status                 =   PUBLISHED;

        return $diveCenter;
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'name'                      =>  'required',
                'image'                     =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:5048',
                'gallery'                   =>  'image_upload_count',
                'gallery.*'                 =>  'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'address'                   =>  'required',
                //'opening_days'              =>  'required',
                'read'                      =>  'required',
            ]);

            $file               =   $request->file('image');
            $galleryImageFiles  =   $request->file('gallery');

            $diveCenter  =   $this->_prepareData($request, $file,$galleryImageFiles);
            $diveCenter  =   ManageDiveCenter::saveDiveCenter($diveCenter);

            /*
             * if user has role like manager , admin, financier etc
             * and they login in merchant section & create shop, dive center,
             * hotel then update their access rights
             */
            if(Auth::user()->is_merchant_user) {
                MerchantUsersRoles::updateSubAccountRights($diveCenter->id, 'centers');
            }

            // save diveCenter image
            if($file){
                $this->_saveImageInLocalDirectory($file, $diveCenter);
            }

            if($galleryImageFiles){
                $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles,$diveCenter);
            }

            return Redirect::to(route('scubaya::merchant::dive_center::dive_centers', [Auth::id()]));

            /*return redirect()->back()->withInput()
                    ->with(['diveCenter' => $diveCenter, 'show_popup' => true]);*/
        }

        $languagesSpoken        =   DB::table('languages')->get();
        $activities             =   Activity::where('non_diving', 0)->get();
        $nonDivingActivities    =   Activity::where('non_diving', 1)->get();
        $facilities             =   Facility::all();
        $specialities           =   Speciality::all();
        $infrastructure         =   Infrastructure::all();
        $paymentMethods         =   PaymentMethod::all();
        $affiliations           =   Affiliations::where('active', 1)->get();

        return view('merchant.dive_center.create')
            ->with([
                'languages_spoken'      => $languagesSpoken,
                'activities'            => $activities,
                'nonDivingActivities'   => $nonDivingActivities,
                'facilities'            => $facilities,
                'specialities'          => $specialities,
                'affiliations'          => $affiliations,
                'infrastructure'        => $infrastructure,
                'paymentMethods'        => $paymentMethods,
                'gears'                 => $this->_filterGearsByCategory()
            ]);
    }

    protected function _filterGearsByCategory()
    {
        $gears          =   Gear::all();

        $filteredGears  =   array();

        if(count($gears)) {
            foreach($gears as $gear) {
                if($gear->category == 'child')
                    $filteredGears['child'][]   =   $gear->name;

                if($gear->category == 'adult')
                    $filteredGears['adult'][]   =   $gear->name;

                if($gear->category == 'other')
                    $filteredGears['other'][]   =   $gear->name;
            }
        }

        return $filteredGears;
    }

    /* edit dive-center information */
    public function update(Request $request)
    {
        if($request->isMethod('post'))
        {
            $this->validate($request, [
                'name'          =>  'required',
                'image'         =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp',
                'gallery'       =>  'sometimes|image_upload_count',
                'gallery.*'     =>  'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
                'address'       =>  'required',
                //'opening_days'  =>  'required',
                'read'          =>  'required'
            ]);

            $file               =   $request->file('image');
            $galleryImageFiles  =   $request->file('gallery');

            $diveCenter  =   $this->_prepareData($request, $file,$galleryImageFiles);
            $diveCenter  =   ManageDiveCenter::updateDiveCenter($request->dive_center_id, $diveCenter);

            //remove existing image of diveCenter and save new one
            if($file){
                $this->_removeImageFromLocalDirectory($diveCenter);
                $this->_saveImageInLocalDirectory($file, $diveCenter);
            }

            //save images for gallery
            if($galleryImageFiles){
                // firstly remove the existing images form gallery then save the new ones
                $this->_removeImagesFromGallery($diveCenter);
                $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $diveCenter);
            }

            return Redirect::to(route('scubaya::merchant::dive_center::dive_centers', [Auth::id()]));
        }

        $diveCenter             =   ManageDiveCenter::find($request->dive_center_id);

        $languagesSpoken        =   DB::table('languages')->get();
        $activities             =   Activity::where('non_diving', 0)->get();
        $nonDivingActivities    =   Activity::where('non_diving', 1)->get();
        $facilities             =   Facility::all();
        $specialities           =   Speciality::all();
        $infrastructure         =   Infrastructure::all();
        $paymentMethods         =   PaymentMethod::all();
        $affiliations           =   Affiliations::where('active', 1)->get();

        return view('merchant.dive_center.edit')->with([
            'diveCenter'            => $diveCenter,
            'languages_spoken'      => $languagesSpoken,
            'activities'            => $activities,
            'nonDivingActivities'   => $nonDivingActivities,
            'facilities'            => $facilities,
            'specialities'          => $specialities,
            'affiliations'          => $affiliations,
            'infrastructure'        => $infrastructure,
            'paymentMethods'        => $paymentMethods,
            'gears'                 => $this->_filterGearsByCategory()
        ]);
    }

    public function delete(Request $request)
    {
        $diveCenter  =   ManageDiveCenter::find($request->dive_center_id);

        //delete dive center image
        $this->_removeImageFromLocalDirectory($diveCenter);

        // remove gallery of dive center
        $this->_removeImagesFromGallery($diveCenter);

        // remove dive center
        ManageDiveCenter::destroy($request->dive_center_id);

        // destroy website details regarding to particular dive center
        /*WebsiteDetails::destroy($request->detail_id);*/

        // delete all docs related to details of website
        /*WebsiteDocumentsMapper::where('website_detail_id', $request->detail_id)->delete();*/

        // delete instructor
        $this->_deleteInstructor($request->dive_center_id);

        // delete boats
        Boat::where('dive_center_id', $request->dive_center_id)->delete();

        // delete courses
        Courses::where('dive_center_id', $request->dive_center_id)->delete();

        // delete dive day planning
        DiveDayPlanning::where('dive_center_id', $request->dive_center_id)->delete();

        if(Auth::user()->is_merchant_user) {
            MerchantUsersRoles::deleteSubAccountRights($request->dive_center_id, 'centers');
        }

        return Redirect::to(route('scubaya::merchant::dive_center::dive_centers', [Auth::id()]));
    }

    protected function _deleteInstructor($diveCenterId)
    {
        $instructors    =   Instructor::where('dive_center_id', $diveCenterId)->get();

        foreach ($instructors as $instructor){
            Instructor::destroy($instructor->id);

            /*delete from user table*/
            User::destroy($instructor->instructor_key);

            /*remove ids from merchant from instructor_ids column*/
            Instructor::removeInstructorIdsFromMerchant($instructor->merchant_ids, $instructor->id);

            /* remove instructor role from user role table */
            MerchantUsersRoles::where('user_id', $instructor->instructor_key)->delete();
        }
    }

    /* save image in directory */
    protected function _saveImageInLocalDirectory($file, $diveCenter)
    {
        $path     =   public_path(). '/assets/images/scubaya/dive_center/'.$diveCenter->merchant_key.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $diveCenter->id.'-'.$diveCenter->image;

        //helper function to compress the image as well as saving at the specified path
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    /* delete image form directory */
    protected function _removeImageFromLocalDirectory($diveCenter)
    {
        $path       =   public_path(). '/assets/images/scubaya/dive_center/'.$diveCenter->merchant_key.'/'.$diveCenter->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    protected function _saveImagesForGalleryInLocalDirectory($galleryImageFiles, $diveCenter)
    {
        $path     =   public_path(). '/assets/images/scubaya/dive_center/gallery/'.$diveCenter->merchant_key.'/'.'diveCenter-'.$diveCenter->id;
        File::makeDirectory($path, 0777, true, true);

        $compressImage = new CompressImage();

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $compressImage->compressImage($file,$path,$filename);
        }
    }

    /* delete images form gallery */
    protected function _removeImagesFromGallery($diveCenter)
    {
        $path     =   public_path(). '/assets/images/scubaya/dive_center/gallery/'.$diveCenter->merchant_key.'/'.'diveCenter-'.$diveCenter->id;
        File::deleteDirectory($path);
    }
}
