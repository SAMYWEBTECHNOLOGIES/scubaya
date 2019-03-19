<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\BoatTypes;
use Illuminate\Http\Request;
use App\Scubaya\model\Boat;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;

class BoatController extends Controller
{
    protected $pagination   =   10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data   =    BoatTypes::paginate($this->pagination);
        $sno    =    (($data->CurrentPage() - 1) * $this->pagination) + 1;
        return view('admin/manage/boats/index',['data'    =>  $data,'sno' =>  $sno]);
    }

    public function addBoatType(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'       =>  'required',
            ]);

            $data        =  $this->_prepareData($request);

            $boat_types =  BoatTypes::addBoatTypes($data);

            // save room profile image
            if($request->file('image')){
                $this->_saveBoatTypeImageInLocalDirectory($request->file('image'), $boat_types->image, $boat_types->id);
            }

            $request->session()->flash('success','Boat Types added successfully.');
            return redirect()->route('scubaya::admin::manage::boat_types');

        }return view('admin.manage.boats.add_boat_type');
    }

    public function editBoatType(Request $request)
    {

        if($request->isMethod('post')){
            $data           =   $this->_prepareData($request);
            $boat_types    =   BoatTypes::updateOrCreate(['id'=> $request->id],$data);

            if($request->file('image')){
                $this->_saveBoatTypeImageInLocalDirectory($request->file('image'), $boat_types->image, $request->id);
            }

            $request->session()->flash('success',$request->name.' updated successfully.');
            return redirect()->route('scubaya::admin::manage::boat_types');
        }

        $data   =   BoatTypes::where('id',$request->id)->first();
        return view('admin.manage.boats.edit_boat_type',['data' =>  $data,'id'  =>  $request->id]);

    }

    public function updateBoatActiveStatus(Request $request){
        $boat   =   BoatTypes::findOrFail($request->boatId);

        $boat->active   =  $request->isActive;
        $boat->update();
    }

    protected function _prepareData($request)
    {
        $data   =   [
            'active'                =>  $request->active,
            'name'                  =>  $request->name,
        ];

        $file               =   $request->file('image');

        if($file){
            $data['image']   =   $file->getClientOriginalName();
        }

        return $data;

    }

    protected function _saveBoatTypeImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/boat_types/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

}
