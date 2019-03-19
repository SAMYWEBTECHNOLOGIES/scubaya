<?php

namespace App\Http\Controllers\Merchant\DiveCenter;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Boat;
use App\Scubaya\model\BoatDriver;
use App\Scubaya\model\BoatTypes;
use App\Scubaya\model\Group;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class BoatController extends Controller
{
    private $authUserId;

    private $noOfBoatsPerPage   =   15;

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

    public function index(Request $request)
    {
        $boats  =   Boat::where('merchant_key', $this->authUserId)
                            ->where('dive_center_id', $request->center_id)
                            ->paginate($this->noOfBoatsPerPage);

        $sno        =   (($boats->currentPage() - 1) * $this->noOfBoatsPerPage) + 1;

        return view('merchant.dive_center.boats.index')
                    ->with('boats', $boats)
                    ->with('sno', $sno)
                    ->with('diveCenterId', $request->center_id);
    }

    protected function _prepareData($request, $file)
    {
        $boat   =   new \stdClass();

        if($file){
            $boat->image       =   $file->getClientOriginalName();
        }

        $boat->merchant_key    =   $this->authUserId;
        $boat->dive_center_id  =   $request->center_id;
        $boat->is_boat_active  =   $request->get('is_boat_active');
        $boat->name            =   $request->get('boat_name');
        $boat->max_passengers  =   $request->get('max_passengers');
        $boat->engine_power    =   $request->get('boat_engine_power');
        $boat->type            =   $request->get('boat_type');
        $boat->driver          =   $request->get('boat_driver');

        return $boat;
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request, [
                'is_boat_active'    =>  'required',
                'boat_name'         =>  'required',
                'max_passengers'    =>  'required',
                'boat_engine_power' =>  'required|numeric',
                'boat_type'         =>  'required',
                'boat_driver'       =>  'required',
                'boat_image'        =>  'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
            ]);

            $file   =   $request->file('boat_image');

            $boat   =   Boat::saveBoats($this->_prepareData($request, $file));

            // save boat image
            if($file){
                $this->_saveImageInLocalDirectory($file, $boat);
            }

            return Redirect::to(route('scubaya::merchant::dive_center::boats', [Auth::id(), $request->center_id]));
        }

        $boat_types = BoatTypes::where('active',1)->get();

        return view('merchant.dive_center.boats.create')
                ->with('drivers', $this->_getDrivers())
                ->with('diveCenterId', $request->center_id)
                ->with('boat_types',$boat_types);
    }

    protected function update(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request, [
                'is_boat_active'    =>  'required',
                'boat_name'         =>  'required',
                'max_passengers'    =>  'required',
                'boat_engine_power' =>  'required|numeric',
                'boat_type'         =>  'required',
                'boat_driver'       =>  'required',
                'boat_image'        =>  'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048'
            ]);

            $file   =   $request->file('boat_image');

            $boat   =   Boat::updateBoats($request->boat_id, $this->_prepareData($request, $file));

            // save boat image
            if($file){
                $this->_removeImageFromLocalDirectory($boat);
                $this->_saveImageInLocalDirectory($file, $boat);
            }

            return Redirect::to(route('scubaya::merchant::dive_center::boats', [Auth::id(), $request->center_id]));
        }

        $boat       =   Boat::find($request->boat_id);

        /*not using where ('active','1') because what if the admin has assigned the following boat's status 0, after when the
        following boat type has already been selected, so in edit mode we need the boatType which was originally there when
        boat was being created.*/
        $boat_types =   BoatTypes::where('active',1)->get();

        return view('merchant.dive_center.boats.edit')
                ->with('boat', $boat)
                ->with('drivers', $this->_getDrivers())
                ->with('boat_types',$boat_types);
        /* we are taking here the boat_types as unique*/
    }

    public function delete(Request $request)
    {
        $boat   =   Boat::find($request->boat_id);

        $this->_removeImageFromLocalDirectory($boat);

        Boat::destroy($request->boat_id);

        return Redirect::to(route('scubaya::merchant::dive_center::boats', [Auth::id(), $request->center_id]));
    }

    protected function _getDrivers() {
        $merchantUserRoles    =   MerchantUsersRoles::where('merchant_id', $this->authUserId)->get();

        $driverId   =   Group::where('name', 'Driver')->value('id');

        $driverIds  =   array();

        foreach($merchantUserRoles as $driver) {
            $groupsIds =   (array)json_decode($driver->group_id);

            foreach ($groupsIds as $key => $value) {
                if ($key == $driverId && $value->is_user_active && $value->confirmed == 1) {
                    array_push($driverIds, $driver->user_id);
                }
            }
        }

        return User::whereIn('id', $driverIds)->get();
    }

    public function updateBoatActiveStatus(Request $request)
    {
        $boat   =   Boat::findOrFail($request->boatId);

        $boat->is_boat_active   =  $request->isActive;
        $boat->update();
    }

    protected function _saveImageInLocalDirectory($file, $boat)
    {
        $path     =   public_path(). '/assets/images/scubaya/boats/'.$boat->merchant_key.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $boat->id.'-'.$boat->image;
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    protected function _removeImageFromLocalDirectory($boat)
    {
        $path       =   public_path(). '/assets/images/scubaya/boats/'.$boat->merchant_key.'/'.$boat->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
