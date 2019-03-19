<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    private $noOfActivityPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // To show all activities
    public function index()
    {
        $activities =   Activity::paginate($this->noOfActivityPerPage);

        $sno        =   (($activities->currentPage() - 1) * $this->noOfActivityPerPage) + 1;

        return view('admin.manage.activities.index')
                ->with('activities', $activities)
                ->with('sno', $sno);
    }

    // prepare activity to store in database
    protected function _prepareActivity($request, $icon)
    {
        $activity   =   new \stdClass();

        $activity->name         =   $request->get('name');

        if($icon) {
            $activity->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        $activity->non_diving   =   $request->get('non_diving');

        return $activity;
    }

    // To create activity
    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This activity is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  'required|unique:activities,name',
                'icon'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $activity   =   Activity::saveActivity($this->_prepareActivity($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $activity);
            }

            return Redirect::to(route('scubaya::admin::manage::activities::index'));
        }

        return view('admin.manage.activities.create');
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The activity is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  ['required', Rule::unique('activities')->ignore($request->id)],
                'icon'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $activity   =   Activity::updateActivity($request->id, $this->_prepareActivity($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($activity->id);
                $this->_saveIconToLocalDirectory($icon, $activity);
            }

            return Redirect::to(route('scubaya::admin::manage::activities::index'));
        }

        $activity   =   Activity::findOrFail($request->id);

        return view('admin.manage.activities.edit')
                ->with('activity', !empty($activity) ? $activity : null);
    }

    public function delete(Request $request)
    {
        Activity::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::activities::index'));
    }

    // save icon to local directory activities
    protected function _saveIconToLocalDirectory($icon, $activity)
    {
        $path     =   public_path(). '/assets/images/scubaya/activities/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $activity->id.'-'.$activity->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    // delete icon from directory activities
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/activities/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    // to update diving activity whether it is non diving or not
    public function isNonDiving(Request $request)
    {
        $activity   =   Activity::findOrFail($request->get('aId'));

        $activity->non_diving   =   $request->get('nonDiving');
        $activity->update();
    }
}
