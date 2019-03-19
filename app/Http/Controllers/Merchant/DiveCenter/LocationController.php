<?php

namespace App\Http\Controllers\Merchant\DiveCenter;


use App\Scubaya\model\Locations;
use App\Scubaya\model\MerchantUsersRoles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LocationController extends Controller
{
    private $authUserId ;

    protected $pagination   =   10;

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

    public function locations()
    {
        $data   =   Locations::where('merchant_key',$this->authUserId)->paginate($this->pagination,['id','active','image','name','need_a_boat','type']);
        $sno    =    (($data->CurrentPage() - 1) * $this->pagination) + 1;

        return view('merchant.dive_center.locations.index')->with([
            'locations'     =>  $data,
            'sno'           =>  $sno
        ]);
    }

    protected function _prepareData(Request $request)
    {
        $data   =   [
            'active'            =>  $request->active,
            'name'              =>  $request->name,
            'latitude'          =>  $request->latitude,
            'longitude'         =>  $request->longitude,
            'type'              =>  $request->type,
            'need_a_boat'       =>  $request->need_a_boat,
            'level'             =>  $request->level
        ];

        if($request->file('image')){
            $file           =   $request->file('image');
            $data['image']  =   $file->getClientOriginalName();
        }

        return $data;
    }

    public function addLocation(Request $request)
    {
        if($request->isMethod('POST')){
            $this->validate($request,[
                'name'          =>  'required',
                'longitude'     =>  'required',
                'latitude'      =>  'required',
                'type'          =>  'required',
                'level'         =>  'required'
            ]);

            $data                   =   $this->_prepareData($request);
            $data['merchant_key']   =   $this->authUserId;

            $location       =   Locations::saveLocation($data);

            if($request->file('image')){
                $this->_saveDestinationImageInLocalDirectory($request->file('image'), $location);
            }

            session()->flash('success','Location created successfully');

            return redirect()->route('scubaya::merchant::dive_center::locations',[Auth::id()]);
        }

        return view('merchant.dive_center.locations.create');
    }

    public function editLocation(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'          =>  'required',
                'longitude'     =>  'required',
                'latitude'      =>  'required',
                'type'          =>  'required',
                'level'         =>  'required'
            ]);

            $data       =   $this->_prepareData($request);
            $location   =   Locations::updateOrCreate(['id' =>  $request->id], $data);

            if($request->file('image')){
                $this->_saveDestinationImageInLocalDirectory($request->file('image'), $location);
            }

            session()->flash('success','Location created successfully');
            return redirect()->route('scubaya::merchant::dive_center::locations',[Auth::id()]);
        }

        $locations   =   Locations::where('id',$request->id)->first();

        return  view('merchant/dive_center/locations/edit')
                    ->with('location', $locations);
    }

    protected function _saveDestinationImageInLocalDirectory($file, $location)
    {
        $path     =   public_path(). '/assets/images/scubaya/locations/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $location->id.'-'.$location->image;
        $file->move($path, $filename);
    }

    public function updateLocationStatus(Request $request)
    {
        $id         =   $request->id;
        $status     =   $request->status;

        Locations::where('id',$id)->update(['active'    =>  $status]);

        return response()->json(['success'  =>  'updated successfully']);
    }
}
