<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\DiveSite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Sabberworm\CSS\Rule\Rule;

class DiveSiteController extends Controller
{
    private $noOfDiveSitePerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * show all dive sites created by admin
     * @return $this
     */
    public function index()
    {
        $diveSites  =   DiveSite::paginate($this->noOfDiveSitePerPage);

        $sno        =   (($diveSites->currentPage() - 1) * $this->noOfDiveSitePerPage) + 1;

        return view('admin.manage.dive_site.index')
            ->with('diveSites', $diveSites)
            ->with('sno', $sno);
    }

    /**
     * prepare dive site to store in database
     * @param $request
     * @param $file
     * @return \stdClass
     */
    protected function _prepareDiveSite($request, $file)
    {
        $diveSite   =   new \stdClass();

        $diveSite->is_active        =   $request->get('active');
        $diveSite->need_a_boat      =   $request->get('need_a_boat');
        $diveSite->name             =   $request->get('name');
        $diveSite->max_depth        =   $request->get('max_depth');
        $diveSite->avg_depth        =   $request->get('avg_depth');
        $diveSite->diver_level      =   $request->get('diver_level');
        $diveSite->current          =   $request->get('current');
        $diveSite->type             =   json_encode($request->get('type'));
        $diveSite->max_visibility   =   $request->get('max_visibility');
        $diveSite->avg_visibility   =   $request->get('avg_visibility');
        $diveSite->latitude         =   $request->get('latitude');
        $diveSite->longitude        =   $request->get('longitude');
        $diveSite->country          =   $request->get('country');

        if($file) {
            $diveSite->image     =   str_replace(" ", "-", $file->getClientOriginalName());
        }

        return $diveSite;
    }

    /**
     * create dive site
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->isMethod('post')) {

            $validator   =   Validator::make($request->all(), [
                'name'    =>  'required',
                'image'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $file       =   $request->file('image');

            $diveSite   =   DiveSite::saveDiveSite($this->_prepareDiveSite($request, $file));

            // save icon to local storage
            if($file) {
                $this->_saveImageToLocalDirectory($file, $diveSite);
            }

            return Redirect::to(route('scubaya::admin::manage::dive_sites::index'));
        }

        return view('admin.manage.dive_site.create');
    }

    /**
     * update dive site
     * @param Request $request
     * @return $this
     */
    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $validator   =   Validator::make($request->all(), [
                'name'    =>  'required',
                'image'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $file       =   $request->file('image');

            $diveSite   =   DiveSite::updateDiveSite($request->id, $this->_prepareDiveSite($request, $file));

            // save icon to local storage
            if($file) {
                $this->_removeImageFromLocalDirectory($diveSite->id);
                $this->_saveImageToLocalDirectory($file, $diveSite);
            }

            return Redirect::to(route('scubaya::admin::manage::dive_sites::index'));
        }

        $diveSite   =   DiveSite::findOrFail($request->id);

        return view('admin.manage.dive_site.edit')
            ->with('diveSite', !empty($diveSite) ? $diveSite : null);
    }

    /**
     * delete dive site by id
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        DiveSite::destroy($request->id);

        $this->_removeImageFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::dive_sites::index'));
    }

    /**
     * save icon to local directory dive sites
     * @param $icon
     * @param $diveSite
     */
    protected function _saveImageToLocalDirectory($icon, $diveSite)
    {
        $path     =   public_path(). '/assets/images/scubaya/dive_sites/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $diveSite->id.'-'.$diveSite->image;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    /**
     * remove icon from local directory dive site
     * @param $id
     */
    protected function _removeImageFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/dive_sites/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    /**
     * update active status of dive site
     * @param Request $request
     */
    public function isActive(Request $request)
    {
        $diveSite   =   DiveSite::findOrFail($request->get('dsId'));

        $diveSite->is_active   =   $request->get('isActive');
        $diveSite->update();
    }

    /**
     * update status of boat of dive site
     * @param Request $request
     */
    public function needABoat(Request $request)
    {
        $diveSite   =   DiveSite::findOrFail($request->get('dsId'));

        $diveSite->need_a_boat   =   $request->get('needABoat');
        $diveSite->update();
    }
}
