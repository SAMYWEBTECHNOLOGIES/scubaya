<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\MerchantPolicies;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\RoomPricing;
use App\Scubaya\model\Rooms;
use App\Scubaya\model\WebsiteDetails;
use App\Scubaya\model\WebsiteDocumentsMapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class HotelInformationController extends Controller
{
    private $authUserId ;

    private $noOfHotelsPerPage   =   15;

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
    /*
     * render form to generate information if it is
     * not created else display form with data to edit
     */
    public function index()
    {
        if(Auth::user()->is_merchant_user) {
            $hotels      =   array();
            $hotelRights =   json_decode(MerchantUsersRoles::where('user_id', Auth::id())->value('sub_account_rights'));

            if($hotelRights) {
                foreach ($hotelRights as $key => $value) {
                    if($key == 'hotel') {
                        array_push($hotels, $value);
                    }
                }
            }

            $hotelInfo    =   Hotel::whereIn('id', array_flatten($hotels))->paginate($this->noOfHotelsPerPage);
        } else {
            $hotelInfo    =   Hotel::where('merchant_primary_id',$this->authUserId)->paginate($this->noOfHotelsPerPage);
        }

        return view('merchant.hotel.index')
            ->with('hotelInfo', $hotelInfo)
            ->with('authId', $this->authUserId);
    }

    /* render form to create hotel */
    public function createHotel()
    {
        $hotelPolicies  =   MerchantPolicies::where('published', 1)
                                            ->where('merchant', HOTEL)->get();

        return view('merchant.hotel.create')->with('hotelPolicies', $hotelPolicies);
    }

    /*
     *  prepare and return hotel information to
     *  save or update before further processing
     */
    protected function _prepareSaveRequestData($request, $file, $galleryImageFiles)
    {
        $hotelInfo  =   new \stdClass();

        $hotelInfo->merchant_primary_id =   $this->authUserId;
        //$hotelInfo->hotel_id            =   $this->_createHotelId();
        $hotelInfo->name                =   $request->get('hotel_name');

        if($file){
            $hotelInfo->image       =   $file->getClientOriginalName();
        }

        if(count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $hotelInfo->gallery  =   json_encode($images);
        }

        $hotelInfo->address         =   $request->get('hotel_address');
        $hotelInfo->city            =   $request->get('hotel_city');
        $hotelInfo->state           =   $request->get('hotel_state');
        $hotelInfo->country         =   $request->get('hotel_country');
        $hotelInfo->zipcode         =   $request->get('hotel_zip_code');
        $hotelInfo->latitude        =   $request->get('hotel_latitude');
        $hotelInfo->longitude       =   $request->get('hotel_longitude');
        $hotelInfo->hotel_desc      =   $request->get('hotel_description');
        $hotelInfo->hotel_policies  =   json_encode($request->get('hotel_policies'));
        $hotelInfo->status          =   PUBLISHED;

        return $hotelInfo;
    }

    /* save hotel information data */
    public function saveHotel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_name'            =>  'required',
            'image'                 =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            'hotel_address'         =>  'required',
            'images_for_gallery'    =>  'required|image_upload_count',
            'images_for_gallery.*'  =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_description'     =>  'required'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file               =   $request->file('image');
        $galleryImageFiles  =   $request->file('images_for_gallery');

        $hotelInfo  =   $this->_prepareSaveRequestData($request, $file, $galleryImageFiles);
        $hotelInfo  =   Hotel::saveHotel($hotelInfo);

        /*
        * if user has role like manager , admin, financier etc
        * and they login in merchant section & create shop, dive center,
        * hotel then update their access rights
        */
        if(Auth::user()->is_merchant_user) {
            MerchantUsersRoles::updateSubAccountRights($hotelInfo->id, 'hotel');
        }

        // save hotel image
        if($file){
            $this->_saveHotelImageInLocalDirectory($file, $hotelInfo);
        }

        //save images for gallery
        if($galleryImageFiles){
            $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $hotelInfo);
        }

        return Redirect::to(route('scubaya::merchant::hotels', [Auth::id()]));

        /*return redirect()->back()->withInput()
               ->with(['hotel' => $hotelInfo, 'show_popup' => true]);*/
    }

    /* prepare and return edit request data */
    protected function _prepareEditRequestData($request, $file)
    {
        $hotelInfo  =   new \stdClass();

        $hotelInfo->name            =   $request->get('hotel_name');

        if($file){
            $hotelInfo->image       =   $file->getClientOriginalName();
        }

        $hotelInfo->address         =   $request->get('hotel_address');
        $hotelInfo->city            =   $request->get('hotel_city');
        $hotelInfo->state           =   $request->get('hotel_state');
        $hotelInfo->country         =   $request->get('hotel_country');
        $hotelInfo->zipcode         =   $request->get('hotel_zip_code');
        $hotelInfo->latitude        =   $request->get('hotel_latitude');
        $hotelInfo->longitude       =   $request->get('hotel_longitude');
        $hotelInfo->hotel_desc      =   $request->get('hotel_description');
        $hotelInfo->hotel_policies  =   json_encode($request->get('hotel_policies'));

        return $hotelInfo;
    }

    /* edit hotel information */
    public function editHotel(Request $request)
    {
        if($request->isMethod('post'))
        {
            $this->validate($request, [
                'hotel_name'             =>  'required',
                'image'                  =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
                'hotel_address'          =>  'required',
                'images_for_gallery'     =>  'image_upload_count',
                'images_for_gallery.*'   =>  'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'hotel_description'      =>  'required'
            ]);

            $file               =   $request->file('image');
            $galleryImageFiles  =   $request->file('images_for_gallery');

            $hotelInfo  =   $this->_prepareEditRequestData($request, $file);
            $hotelInfo  =   Hotel::updateHotel($request->hotel_id, $hotelInfo);

            //remove existing image of hotel and save new one
            if($file){
                $this->_removeHotelImageInLocalDirectory($hotelInfo);
                $this->_saveHotelImageInLocalDirectory($file, $hotelInfo);
            }

            //save images for gallery
            if($galleryImageFiles){
                // firstly remove the existing images form gallery then save the new ones
                $this->_removeImagesFromGallery($hotelInfo);
                $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $hotelInfo);
            }

            return Redirect::to(route('scubaya::merchant::hotels', [Auth::id()]));
        }

        $hotelInfo  =   Hotel::find($request->hotel_id);

        return view('merchant.hotel.edit')->with('hotelInfo', $hotelInfo);
    }

    /* delete hotel information */
    public function deleteHotel(Request $request)
    {
        $hotelInfo  =   Hotel::find($request->hotel_id);

        //delete hotel image
        $this->_removeHotelImageInLocalDirectory($hotelInfo);

        // remove gallery of hotel
        $this->_removeImagesFromGallery($hotelInfo);

        // remove hotel
        Hotel::destroy($request->hotel_id);

        // remove room pricing / tariff
        $this->_deleteTariff($request->hotel_id);

        // remove rooms
        Rooms::where('merchant_primary_id', $this->authUserId)
            ->where('hotel_id', $request->hotel_id)->delete();

        // destroy website details regarding to particular dive center
        /*WebsiteDetails::destroy($request->detail_id);*/

        // delete all docs related to details of website
        /*WebsiteDocumentsMapper::where('website_detail_id', $request->detail_id)->delete();*/

        if(Auth::user()->is_merchant_user) {
            MerchantUsersRoles::deleteSubAccountRights($request->hotel_id, 'hotel');
        }

        return Redirect::to(route('scubaya::merchant::hotels', [Auth::id()]));
    }

    protected function _deleteTariff($hotelId)
    {
        $rooms  =   Rooms::where('merchant_primary_id', $this->authUserId)
            ->where('hotel_id', $hotelId)->get();

        foreach ($rooms as $room) {
            RoomPricing::where('room_id', $room->id)->delete();
        }
    }

    /*
     * save hotel image in local directory if it does not exists
     * else remove the older image and save new one
     */
    protected function _saveHotelImageInLocalDirectory($file, $hotelInfo)
    {
        $path     =   public_path(). '/assets/images/scubaya/hotel/'.$hotelInfo->merchant_primary_id.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename =   $hotelInfo->id.'-'.$hotelInfo->image;

        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    /* delete hotel image form directory */
    protected function _removeHotelImageInLocalDirectory($hotelInfo)
    {
        $path       =   public_path(). '/assets/images/scubaya/hotel/'.$hotelInfo->merchant_primary_id.'/'.$hotelInfo->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    /* create unique hotel id */
    protected function _createHotelId()
    {
        $randomInt =   random_int(10000000,99999999);
        return 'HOTEL'.$randomInt;
    }

    /* save images for room gallery section in local directory */
    protected function _saveImagesForGalleryInLocalDirectory($galleryImageFiles, $hotelInfo)
    {
        $path     =   public_path(). '/assets/images/scubaya/hotel/gallery/'.$hotelInfo->merchant_primary_id.'/'.'hotel-'.$hotelInfo->id;
        File::makeDirectory($path, 0777, true, true);

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $compressImage = new CompressImage();
            $compressImage->compressImage($file,$path,$filename);
        }
    }

    /* delete images form gallery */
    protected function _removeImagesFromGallery($hotelInfo)
    {
        $path     =   public_path(). '/assets/images/scubaya/hotel/gallery/'.$hotelInfo->merchant_primary_id.'/'.'hotel-'.$hotelInfo->id;
        File::deleteDirectory($path);
    }
}
