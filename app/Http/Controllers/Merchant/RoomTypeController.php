<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\RoomTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class RoomTypeController extends Controller
{
    private $authUserId ;

    private $noOfTypesPerPage   =   15;

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

    /* list all the room types */
    public function index()
    {
        $roomTypes  =   RoomTypes::where('merchant_primary_id', $this->authUserId)->paginate($this->noOfTypesPerPage);

        $sno        =   (($roomTypes->currentPage() - 1) * $this->noOfTypesPerPage) + 1;

        return view('merchant.hotel.room_types.index')
            ->with('roomTypes', $roomTypes)
            ->with('sno', $sno);
    }

    /* render form to create a room type */
    public function createRoomType()
    {
        return view('merchant.hotel.room_types.create');
    }

    /*
     * prepare and return room type data to save
     * or update before further processing
     */
    public function _prepareData($file, $request)
    {
        $roomType    =   new \stdClass();

        // if request has icon then prepare it to save in database
        if($file){
            $roomType->icon  =   $file->getClientOriginalName();
        }

        $roomType->merchant_primary_id  =   $this->authUserId;
        $roomType->room_type            =   ucwords($request->get('room_type'));

        return $roomType;
    }

    /* save room type */
    public function saveRoomType(Request $request)
    {
        $this->validate($request, [
            'room_type'         => 'required',
            'room_type_icon'    => 'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
        ]);

        $file       =   $request->file('room_type_icon');
        $roomType   =   $this->_prepareData($file, $request);
        $roomType   =   RoomTypes::saveRoomType($roomType);

        // if request has icon then save it in local directory
        if($file){
            $insertedId =   $roomType->id;
            $this->_saveRoomTypeIconInLocalDirectory($file, $roomType->icon, $insertedId);
        }

        return Redirect::to(route('scubaya::merchant::room_types', [Auth::id()]));
    }

    /* edit room type */
    public function editRoomType(Request $request)
    {
        $roomType    =  RoomTypes::find($request->room_type_id);

        return view('merchant.hotel.room_types.edit')->with('roomType', $roomType);
    }

    /* update room type in database */
    public function updateRoomType(Request $request)
    {
        $this->validate($request, [
            'room_type'       => 'required',
            'room_type_icon'  => 'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
        ]);

        $roomTypeId   =   $request->room_type_id;
        $file         =   $request->file('room_type_icon');

        $roomType     =   $this->_prepareData($file, $request);
        $roomType     =   RoomTypes::updateRoomType($roomTypeId, $roomType);

        // id update request has icon then
        // remove the previous icon and save
        // the new one in local directory
        if($file){
            $updatedId =   $roomType->id;
            $this->_removeRoomTypeIconFromLocalDirectory($updatedId);
            $this->_saveRoomTypeIconInLocalDirectory($file, $roomType->icon, $updatedId);
        }

        return Redirect::to(route('scubaya::merchant::room_types', [Auth::id()]));
    }

    /* delete room type */
    public function deleteRoomType(Request $request)
    {
        RoomTypes::destroy($request->room_type_id);
        $this->_removeRoomTypeIconFromLocalDirectory($request->room_type_id);
        return Redirect::to(route('scubaya::merchant::room_types', [Auth::id()]));
    }

    /* save room type icon in local directory */
    protected function _saveRoomTypeIconInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/room_types/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    /* remove room type icon from local directory */
    protected function _removeRoomTypeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/room_types/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
