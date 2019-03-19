<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DiveCenterFacilityController extends Controller
{
    private $noOfFacilityPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // To show all facilities
    public function index()
    {
        $facilities =   Facility::paginate($this->noOfFacilityPerPage);

        $sno        =   (($facilities->currentPage() - 1) * $this->noOfFacilityPerPage) + 1;

        return view('admin.manage.dive_center_facility.index')
            ->with('facilities', $facilities)
            ->with('sno', $sno);
    }

    // prepare facility to store in database
    protected function _prepareFacility($request, $icon)
    {
        $facility   =   new \stdClass();

        $facility->name         =   $request->get('name');

        if($icon) {
            $facility->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        return $facility;
    }

    // To create facility
    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This facility is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  'required|unique:dive_center_facilities,name',
                'icon'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $facility   =   Facility::saveFacility($this->_prepareFacility($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $facility);
            }

            return Redirect::to(route('scubaya::admin::manage::center_facility::index'));
        }

        return view('admin.manage.dive_center_facility.create');
    }

    // to update facility
    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The facility is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  ['required', Rule::unique('dive_center_facilities')->ignore($request->id)],
                'icon'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $facility   =   Facility::updateFacility($request->id, $this->_prepareFacility($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($facility->id);
                $this->_saveIconToLocalDirectory($icon, $facility);
            }

            return Redirect::to(route('scubaya::admin::manage::center_facility::index'));
        }

        $facility   =   Facility::findOrFail($request->id);

        return view('admin.manage.dive_center_facility.edit')
            ->with('facility', !empty($facility) ? $facility : null);
    }

    // to delete facility
    public function delete(Request $request)
    {
        Facility::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::center_facility::index'));
    }

    // save icon to local directory dive_center_facility
    protected function _saveIconToLocalDirectory($icon, $facility)
    {
        $path     =   public_path(). '/assets/images/scubaya/dive_center_facility/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $facility->id.'-'.$facility->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    // delete icon from directory dive_center_facility
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/dive_center_facility/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
