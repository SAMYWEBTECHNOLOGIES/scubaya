<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\GlobalSetting;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\RoomFeatures;
use App\Scubaya\model\RoomPricing;
use App\Scubaya\model\RoomPricingSettings;
use App\Scubaya\model\Rooms;
use App\Scubaya\model\RoomTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
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

    /* list all the rooms created by merchant */
    public function index(Request $request)
    {
        $rooms          =   Rooms::where('merchant_primary_id', $this->authUserId)
                                    ->where('hotel_id', $request->hotel_id)
                                    ->paginate($this->noOfRoomsPerPage);

        $hotelName      =   Hotel::where('id', $request->hotel_id)->first();

        $sno            =   (($rooms->currentPage() - 1) * $this->noOfRoomsPerPage) + 1;

        return view('merchant.hotel.room_details.index')
                ->with('rooms', $rooms)
                ->with('hotelName', $hotelName)
                ->with('sno', $sno);
    }

    /* render the form for creating room */
    public function createRoom(Request $request)
    {
        $features   =   RoomFeatures::where('merchant_primary_id', $this->authUserId)->get();
        $roomTypes  =   RoomTypes::where('merchant_primary_id', $this->authUserId)->get();

        return view('merchant.hotel.room_details.create')
            ->with('features', $features)
            ->with('roomTypes', $roomTypes)
            ->with('hotelId', $request->hotel_id);
    }

    /*
     * prepare and return the room data to save
     * or update before further processing
     */
    protected function _prepareData($request, $file, $galleryImageFiles)
    {
        $room   =   new \stdClass();

        if($file){
            $room->room_image   =   $file->getClientOriginalName();
        }

        if($galleryImageFiles && count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $room->gallery  =   json_encode($images);
        }

        $room->merchant_primary_id  =   $this->authUserId;
        $room->hotel_id             =   $request->hotel_id;
        $room->type                 =   $request->get('room_type');
        $room->name                 =   $request->get('room_name');
        $room->number               =   $request->get('room_number');
        $room->floor                =   $request->get('floor');
        $room->max_people           =   $request->get('max_people');
        $room->features             =   json_encode($request->get('features'));
        $room->description          =   $request->get('room_description');

        return $room;
    }

    /* save room data */
    public function saveRoom(Request $request)
    {
        $this->validate($request,[
            'room_type'         =>  'required',
            'floor'             =>  'integer',
            'room_number'       =>  'integer|room_number_exists:'.$request->hotel_id.', NULL, '.$request->room_number,
            'image'             =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048|dimensions:max_width=357,max_height=238',
            'room_description'  =>  'required'
        ]);

        $file               =   $request->file('image');
        $galleryImageFiles  =   $request->file('room_images_for_gallery');

        // save room information
        $room       =   $this->_prepareData($request, $file, $galleryImageFiles);
        $room       =   Rooms::saveRoom($room);

        // save room profile image
        if($file){
            $this->_saveRoomImageInLocalDirectory($file, $room->room_image, $room->id);
        }

        //save images for gallery
        if($galleryImageFiles){
            $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $room->id);
        }

        return Redirect::to(route('scubaya::merchant::all_rooms', [Auth::id(), $room->hotel_id]));
    }

    /* edit room data */
    public function editRoom(Request $request)
    {
        $room       =   Rooms::find($request->room_id);
        $features   =   RoomFeatures::where('merchant_primary_id', $this->authUserId)->get();
        $roomTypes  =   RoomTypes::where('merchant_primary_id', $this->authUserId)->get();

        return view('merchant.hotel.room_details.edit')
                ->with('room', $room)
                ->with('features', $features)
                ->with('roomTypes', $roomTypes);
    }

    /* update room data in database */
    public function updateRoom(Request $request)
    {
        $this->validate($request,[
            'room_type'         =>  'required',
            'floor'             =>  'integer',
            'room_number'       =>  'integer|room_number_exists:'.$request->hotel_id.','.$request->old_room_number.','.$request->room_number,
            'image'             =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048|dimensions:max_width=357,max_height=238',
            'room_description'  =>  'required'
        ]);

        $roomId             =   $request->room_id;
        $file               =   $request->file('image');
        $galleryImageFiles  =   $request->file('room_images_for_gallery');

        $room       =   $this->_prepareData($request, $file, $galleryImageFiles);
        $room       =   Rooms::updateRoom($roomId, $room);

        // save room profile image
        if($file){
            // firstly remove the existing image form directory then save the new one
            $this->_removeRoomImageFromLocalDirectory($roomId);
            $this->_saveRoomImageInLocalDirectory($file, $room->room_image, $roomId);
        }

        //save images for gallery
        if($galleryImageFiles){
            // firstly remove the existing images form gallery then save the new ones
            $this->_removeImagesFromGallery($roomId);
            $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $roomId);
        }

        return Redirect::to(route('scubaya::merchant::all_rooms', [Auth::id(), $room->hotel_id]));
    }

    /* delete room data */
    public function deleteRoom(Request $request)
    {
        Rooms::destroy($request->room_id);

        $this->_removeRoomImageFromLocalDirectory($request->room_id);
        $this->_removeImagesFromGallery($request->room_id);

        RoomPricing::where('merchant_primary_id', $this->authUserId)
                    ->where('room_id', $request->room_id)->delete();

        return Redirect::to(route('scubaya::merchant::all_rooms', [Auth::id(), $request->hotel_id]));
    }

    /* save room profile image in local directory */
    protected function _saveRoomImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/rooms/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    /* save images for room gallery section in local directory */
    protected function _saveImagesForGalleryInLocalDirectory($galleryImageFiles, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/rooms/gallery/room-'.$id;
        File::makeDirectory($path, 0777, true, true);

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $compressImage = new CompressImage();
            $compressImage->compressImage($file,$path,$filename);
        }
    }

    /* delete image form directory */
    protected function _removeRoomImageFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/rooms/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    /* delete images form gallery */
    protected function _removeImagesFromGallery($id)
    {
        $path     =   public_path(). '/assets/images/scubaya/rooms/gallery/room-'.$id;
        File::deleteDirectory($path);
    }
}
