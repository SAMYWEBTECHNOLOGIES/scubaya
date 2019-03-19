<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Scubaya\model\Admin;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function adminProfile(Request $request)
    {
        $adminDetail =  User::where('id',Auth::id())->first();
        $adminTitle  =  Admin::where('admin_key',Auth::id())->first();

        if($request->isMethod('post')){

            $this->validate($request, [
                'first_name'    =>  'required',
                'last_name'     =>  'required',
                'email'         =>  'required|email|unique:users,email,'.Auth::id().',id,is_admin,'.IS,
            ]);

            $adminDetail->first_name   =   $request->first_name;
            $adminDetail->last_name    =   $request->last_name;
            $adminDetail->email        =   $request->email;

            if($request->password){
                $this->validate($request,[
                    'password'   =>  'min:6',
                ]);
                $adminDetail->password     =   bcrypt($request->password);
            }

            $adminDetail->update();

            if($request->title){
                $adminTitle->title = $request->title;
                $adminTitle->update();
            }
            $request->session()->flash('success','Profile updated successfully');

            return  redirect()->back();
        }

        return view('admin.profile.index')
                ->with('adminTitle',$adminTitle)
                ->with('adminDetail',$adminDetail);
    }
}
