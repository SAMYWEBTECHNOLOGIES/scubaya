<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\RoomFeatures;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RoomFeaturesController extends Controller
{
    private $authUserId;

    private $noOfFeaturesPerPage    =   15;

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

    /* list all the room features created by merchant */
    public function index()
    {
        $roomFeatures   =   RoomFeatures::where('merchant_primary_id', $this->authUserId)->paginate($this->noOfFeaturesPerPage);

        $sno            =   (($roomFeatures->currentPage() - 1) * $this->noOfFeaturesPerPage) + 1;

        return view('merchant.hotel.room_features.index')
            ->with('roomFeatures', $roomFeatures)
            ->with('sno', $sno);
    }

    /* render form to create room features */
    public function createRoomFeature()
    {
        return view('merchant.hotel.room_features.create');
    }

    /*
     * prepare and return room feature data to
     * save or update before further processing
     */
    public function _prepareData($file, $request)
    {
        $feature    =   new \stdClass();

        // if request has icon then prepare it to save in database
        if($file){
            $feature->icon  =   $file->getClientOriginalName();
        }

        $feature->merchant_primary_id   =   $this->authUserId;
        $feature->feature_description   =   $request->get('feature_description');

        return $feature;
    }

    /* save room feature */
    public function saveRoomFeature(Request $request)
    {
        $this->validate($request, [
            'feature_description'   => 'required',
            'feature_icon'          => 'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
        ]);

        $file      =   $request->file('feature_icon');
        $feature   =   $this->_prepareData($file, $request);
        $feature   =   RoomFeatures::saveFeature($feature);

        // if request has icon then save it in local directory
        if($file){
            $insertedId =   $feature->id;
            $this->_saveFeatureIconInLocalDirectory($file, $feature->icon, $insertedId);
        }

        return Redirect::to(route('scubaya::merchant::room_features', [Auth::id()]));
    }

    /* edit room feature */
    public function editRoomFeature(Request $request)
    {
        $features    =  RoomFeatures::find($request->feature_id);

        return view('merchant.hotel.room_features.edit')->with('features', $features);
    }

    /* update room feature data in database */
    public function updateRoomFeature(Request $request)
    {
        $this->validate($request, [
            'feature_description'   => 'required',
            'feature_icon'          =>  'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
        ]);

        $featureId   =   $request->feature_id;
        $file        =   $request->file('feature_icon');

        $feature     =   $this->_prepareData($file, $request);
        $feature     =   RoomFeatures::updateFeature($featureId, $feature);

        // if update request has icon to edit then
        // remove the previous icon and save the new one
        if($file){
            $updatedId =   $feature->id;
            $this->_removeFeatureIconFromLocalDirectory($updatedId);
            $this->_saveFeatureIconInLocalDirectory($file, $feature->icon, $updatedId);
        }

        return Redirect::to(route('scubaya::merchant::room_features', [Auth::id()]));
    }

    /* delete room feature */
    public function deleteRoomFeature(Request $request)
    {
        RoomFeatures::destroy($request->feature_id);
        $this->_removeFeatureIconFromLocalDirectory($request->feature_id);
        return Redirect::to(route('scubaya::merchant::room_features', [Auth::id()]));
    }

    /* save room feature icon in local directory */
    protected function _saveFeatureIconInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/room_features/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    /* remove room feature icon form local directory */
    protected function _removeFeatureIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/room_features/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
