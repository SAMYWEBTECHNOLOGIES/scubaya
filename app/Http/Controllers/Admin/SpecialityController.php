<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Speciality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SpecialityController extends Controller
{
    private $noOfSpecialityPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // To show all speciality
    public function index()
    {
        $specialities   =   Speciality::paginate($this->noOfSpecialityPerPage);

        $sno            =   (($specialities->currentPage() - 1) * $this->noOfSpecialityPerPage) + 1;

        return view('admin.manage.speciality.index')
            ->with('specialities', $specialities)
            ->with('sno', $sno);
    }

    // prepare speciality to store in database
    protected function _prepareSpeciality($request, $icon)
    {
        $speciality   =   new \stdClass();

        $speciality->name         =   $request->get('name');

        if($icon) {
            $speciality->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        return $speciality;
    }

    // To create speciality
    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This speciality is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  'required|unique:specialities,name',
                'icon'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon         =   $request->file('icon');

            $speciality   =   Speciality::saveSpeciality($this->_prepareSpeciality($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $speciality);
            }

            return Redirect::to(route('scubaya::admin::manage::speciality::index'));
        }

        return view('admin.manage.speciality.create');
    }

    // to update speciality
    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The speciality is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  ['required', Rule::unique('specialities')->ignore($request->id)],
                'icon'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon         =   $request->file('icon');

            $speciality   =   Speciality::updateSpeciality($request->id, $this->_prepareSpeciality($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($speciality->id);
                $this->_saveIconToLocalDirectory($icon, $speciality);
            }

            return Redirect::to(route('scubaya::admin::manage::speciality::index'));
        }

        $speciality   =   Speciality::findOrFail($request->id);

        return view('admin.manage.speciality.edit')
            ->with('speciality', !empty($speciality) ? $speciality : null);
    }

    // to delete speciality
    public function delete(Request $request)
    {
        Speciality::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::speciality::index'));
    }

    // save icon to local directory speciality
    protected function _saveIconToLocalDirectory($icon, $speciality)
    {
        $path     =   public_path(). '/assets/images/scubaya/speciality/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $speciality->id.'-'.$speciality->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    // delete icon from directory speciality
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/speciality/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
