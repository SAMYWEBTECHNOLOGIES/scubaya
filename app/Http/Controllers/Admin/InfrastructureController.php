<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Infrastructure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InfrastructureController extends Controller
{
    private $noOfInfrastructurePerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $infrastructures =   Infrastructure::paginate($this->noOfInfrastructurePerPage);

        $sno             =   (($infrastructures->currentPage() - 1) * $this->noOfInfrastructurePerPage) + 1;

        return view('admin.manage.infrastructure.index')
                ->with('infrastructures', $infrastructures)
                ->with('sno', $sno);
    }

    // prepare activity to store in database
    protected function _prepareInfrastructure($request, $icon)
    {
        $infrastructure   =   new \stdClass();

        $infrastructure->name         =   $request->get('name');

        if($icon) {
            $infrastructure->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        return $infrastructure;
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This infrastructure is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  'required|unique:infrastructure,name',
                'icon'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon       =   $request->file('icon');

            $infrastructure   =   Infrastructure::saveInfrastructure($this->_prepareInfrastructure($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $infrastructure);
            }

            return Redirect::to(route('scubaya::admin::manage::infrastructure::index'));
        }

        return view('admin.manage.infrastructure.create');
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The infrastructure is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  ['required', Rule::unique('infrastructure')->ignore($request->id)],
                'icon'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon           =   $request->file('icon');

            $infrastructure =   Infrastructure::updateInfrastructure($request->id, $this->_prepareInfrastructure($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($infrastructure->id);
                $this->_saveIconToLocalDirectory($icon, $infrastructure);
            }

            return Redirect::to(route('scubaya::admin::manage::infrastructure::index'));
        }

        $infrastructure   =   Infrastructure::findOrFail($request->id);

        return view('admin.manage.infrastructure.edit')
                ->with('infrastructure', $infrastructure);
    }

    public function delete(Request $request)
    {
        Infrastructure::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::infrastructure::index'));
    }

    // save icon to local directory infrastructure
    protected function _saveIconToLocalDirectory($icon, $infrastructure)
    {
        $path     =   public_path(). '/assets/images/scubaya/infrastructure/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $infrastructure->id.'-'.$infrastructure->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon, $path, $filename);
    }

    // delete icon from directory infrastructure
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/infrastructure/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
