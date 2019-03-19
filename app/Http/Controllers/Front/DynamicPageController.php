<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\model\DynamicContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicPageController extends Controller
{
    public function dynamicPage(Request $request)
    {
        $content    =   DynamicContent::where('slug',$request->slug)->first(['content','name']);
        return view('front.dynamic_pages.dynamic_content',['content'    =>  $content]);
    }
}
