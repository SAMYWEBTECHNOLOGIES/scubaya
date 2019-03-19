<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\MarineLife;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MarineLifeController extends Controller
{
    protected $pagination   =   10;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data   =    MarineLife::paginate($this->pagination);
        $sno    =    (($data->CurrentPage() - 1) * $this->pagination) + 1;

        return view('admin.manage.marine_life.index',['data'    =>  $data,'sno' =>  $sno]);
    }

    public function addMarineLife(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'common_name'       =>  'required',
            ]);

            $data        =  $this->_prepareData($request);

            $marine_life =  MarineLife::addMarineLife($data);

            // save room profile image
            if($request->file('main_image')){
                $this->_saveMarineImageInLocalDirectory($request->file('main_image'), $marine_life->main_image, $marine_life->id);
            }

            //save images for gallery
            if($request->file('max_images')){
                $this->_savesMarineGalleryInLocalDirectory($request->file('max_images'), $marine_life->id);
            }

            $request->session()->flash('success','Marine Life added successfully.');
            return redirect()->route('scubaya::admin::manage::marine_life');

        } return view('admin.manage.marine_life.add_marine_life');
    }

    public function editMarineLife(Request $request)
    {
        if($request->isMethod('post')){
            $data           =   $this->_prepareData($request);
            $marine_life    =   MarineLife::updateOrCreate(['id'=> $request->id],$data);

            if($request->file('main_image')){
                $this->_saveMarineImageInLocalDirectory($request->file('main_image'), $marine_life->main_image, $request->id);
            }

            $request->session()->flash('success',$request->common_name.' updated successfully.');
            return redirect()->route('scubaya::admin::manage::marine_life');
        }

        $data   =   MarineLife::where('id',$request->id)->first();
        return view('admin.manage.marine_life.edit_marine_life',['data' =>  $data,'id'  =>  $request->id]);
    }

    protected function _prepareData($request)
    {
        $data   =   [
            'active'            =>  $request->active,
            'common_name'       =>  $request->common_name,
            'scientific_name'   =>  $request->scientific_name,
            'description'       =>  $request->description,
        ];

        $file               =   $request->file('main_image');
        $galleryImageFiles  =   $request->file('max_images');

        if($file){
            $data['main_image']   =   $file->getClientOriginalName();
        }

        if(count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $data['max_images']  =   json_encode($images);
        }

        return $data;
    }

    protected function _saveMarineImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/marine_life/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    protected function _savesMarineGalleryInLocalDirectory($galleryImageFiles, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/marine_life/marine_life-'.$id;
        File::makeDirectory($path, 0777, true, true);

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $file->move($path, $filename);
        }
    }
}
