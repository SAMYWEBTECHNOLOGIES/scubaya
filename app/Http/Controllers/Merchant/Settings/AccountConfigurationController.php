<?php

namespace App\Http\Controllers\Merchant\Settings;

use App\Scubaya\model\User;
use App\Scubaya\model\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountDetails(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'first_name'    =>  'required',
                'last_name'     =>  'required',
                'password'      =>  'sometimes|confirmed',
                'email'         =>  'required|email|unique:users,email,'.Auth::id().',id,is_merchant,'.IS,
            ]);

            $user               =   User::find(Auth::id());

            $user->first_name   =   $request->first_name;
            $user->last_name    =   $request->last_name;
            $user->email        =   $request->email;

            if($request->password){
                $user->password     =   bcrypt($request->password);
            }

            $user->update();

            $request->session()->flash('success','Updated Successfully.');

            return redirect()->route('scubaya::merchant::settings::account_configuration', [Auth::id()]);
        }

        $data   =   User::where('id',Auth::id())->first(['first_name','last_name','email']);

        return view('merchant.settings.account_configuration.account_configuration')
                    ->with('accountDetail',$data);
    }

}
