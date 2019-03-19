<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\DynamicContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DynamicPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data   =   DynamicContent::all();
        return view('admin.manage.dynamic_pages.index')->with('dynamic_pages',$data);
    }

    public function addPage(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'              =>  'required',
                'slug'              =>  'required|unique:dynamic_contents',
                'dynamic_content'   =>  'required'
            ]);

            $data   =   $this->_prepare($request);

            DynamicContent::saveDynamicContent($data);

            $request->session()->flash('success',$request->name.' Page Created Successfully');
            return redirect(route('scubaya::admin::manage::dynamic_pages'));
        }

        return view('admin.manage.dynamic_pages.create');
    }

    public function editPage(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'              =>  'required',
                'slug'              =>  'required|unique:dynamic_contents,slug,'.$request->id,
                'dynamic_content'   =>  'required'
            ]);

            $data   =   $this->_prepare($request);

            DynamicContent::updateOrCreate(['id'    =>  $request->id],$data);
            $request->session()->flash('success',$request->name.' Page Updated Successfully');
            return redirect(route('scubaya::admin::manage::dynamic_pages'));
        }

        $data   =   DynamicContent::find($request->id);
        return  view('admin.manage.dynamic_pages.edit',['data'    =>  $data]);
    }

    public function deletePage(Request $request)
    {
        DynamicContent::destroy($request->id);

        $request->session()->flash('success','Page Deleted Successfully');
        return redirect(route('scubaya::admin::manage::dynamic_pages'));
    }

    protected function _prepare(Request $request)
    {
        return  [
            'active'    =>  $request->active,
            'name'      =>  $request->name,
            'slug'      =>  str_replace(' ','_',$request->slug),
            'content'   =>  html_entity_decode($request->dynamic_content),
        ];
    }
}
