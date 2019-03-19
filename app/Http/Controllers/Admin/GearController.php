<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Gear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Sabberworm\CSS\Rule\Rule;

class GearController extends Controller
{
    private $noOfGearPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // To show all gears
    public function index()
    {
        $gears   =   Gear::paginate($this->noOfGearPerPage);

        $sno     =   (($gears->currentPage() - 1) * $this->noOfGearPerPage) + 1;

        return view('admin.manage.scuba_gear.index')
            ->with('gears', $gears)
            ->with('sno', $sno);
    }

    // prepare gear to store in database
    protected function _prepareGear($request, $icon)
    {
        $gear   =   new \stdClass();

        $gear->name         =   $request->get('name');

        if($icon) {
            $gear->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        $gear->category     =   $request->get('category');

        return $gear;
    }

    // To create gear
    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This gear is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'      =>  'required',/*|unique:gears,name*/
                'icon'      =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
                'category'  =>  'required'
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $gear   =   Gear::saveGear($this->_prepareGear($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $gear);
            }

            return Redirect::to(route('scubaya::admin::manage::gear::index'));
        }

        return view('admin.manage.scuba_gear.create');
    }

    // to update gear
    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The gear is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'      =>  'required',/* Rule::unique('gears')->ignore($request->id)],*/
                'icon'      =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
                'category'  =>  'required'
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon   =   $request->file('icon');

            $gear   =   Gear::updateGear($request->id, $this->_prepareGear($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($gear->id);
                $this->_saveIconToLocalDirectory($icon, $gear);
            }

            return Redirect::to(route('scubaya::admin::manage::gear::index'));
        }

        $gear   =   Gear::findOrFail($request->id);

        return view('admin.manage.scuba_gear.edit')
            ->with('gear', !empty($gear) ? $gear : null);
    }

    // to delete gear
    public function delete(Request $request)
    {
        Gear::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::gear::index'));
    }

    // save icon to local directory gears
    protected function _saveIconToLocalDirectory($icon, $gear)
    {
        $path     =   public_path(). '/assets/images/scubaya/gears/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $gear->id.'-'.$gear->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    // delete icon from directory gears
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/gears/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
