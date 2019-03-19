<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\Affiliations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;


class AffiliateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $affiliations   =   Affiliations::all(['name','international','image','id','active']);
        return view('admin.manage.affiliates.index',['affiliations'=>$affiliations]);
    }

    public function addAffiliate(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'affiliation_name'   =>  'required',
                'image'              =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ]);

            $data   =   [
                'name'              =>  $request->affiliation_name,
                'active'            =>  $request->active,
                'international'     =>  $request->international,
            ];

            $file               =   $request->file('image');
            if($file){
                $data['image']   =   $file->getClientOriginalName();
            }

            $affiliate  =   Affiliations::saveAffiliation($data);

            if($request->file('image')){
                $this->_saveAffiliateImageInLocalDirectory($request->file('image'), $affiliate->image, $affiliate->id);
            }

            $request->session()->flash('success','Affiliation add successfully');

            return redirect()->route('scubaya::admin::manage::affiliates');
        }
        return view('admin.manage.affiliates.add_affiliate');
    }

    protected function _saveAffiliateImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/affiliations/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    public function updateAffiliateStatus(Request $request)
    {
        $affiliate           =   Affiliations::findOrFail($request->affiliateId);

        $affiliate->active   =   $request->isActive;
        $affiliate->update();
    }

    /* remove document from local directory */
    protected function _removeDocumentFromLocalDirectory($id, $filename)
    {
        $path = public_path() . '/assets/images/scubaya/affiliations/'.$id.'-'.$filename;
        File::delete($path);
    }
}
