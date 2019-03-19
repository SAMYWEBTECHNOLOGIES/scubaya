<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scubaya\model\HomePageContent;

class DynamicHomepageController extends Controller
{
    public function index(Request $request)
    {
        if($request->isMethod('post')){

            $data           =   $this->__prepare($request);

            HomePageContent::saveHomepageContent($data);

            $request->session()->flash('success','Content updated successfully');

            return  redirect()->back();

        }

        return view('admin.manage.home_page.index')->with('homepageContent',HomePageContent::first());
    }

    protected function __prepare(Request $request)
    {
        if($request->dynamic_features_content[0]!=null){

            $features_content = json_encode($request->dynamic_features_content);
        }
        else {
            $features_content = null;
        }
        return  [
            'subscription_content'  =>  $request->dynamic_subscription_content,
            'blog_content'          =>  $request->dynamic_blog_content,
            'features_content'      =>  $features_content,
        ];
    }

}
