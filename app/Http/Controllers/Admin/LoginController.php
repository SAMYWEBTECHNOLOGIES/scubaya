<?php

namespace App\Http\Controllers\Admin;
use App\Scubaya\model\Admin;
use App\Scubaya\model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    protected $admin;
    protected $user;
    public function __construct()
    {
        $this->middleware('guest',['except'=>['logout']]);
        $this->admin    =   new Admin();
        $this->user     =   new User();
    }

    public function  login(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->validate($request, [
                'email'     => 'required|email',
                'password'  => 'required',
            ]);

            $admin_key      =   $this->user->where([['email',$request->email],['is_admin',IS]])->value('id');

            if($admin_key){
                $check          =   $this->admin->where([['admin_key', $admin_key],['block', '=', 0]])
                                                ->exists();

                /*Admin will block if he attempt incorrectly*/
                if (DB::table('admins')->where([['admin_key', $admin_key], ['block', 1]])
                    ->when($check, function ($query) use ($request,$admin_key) {
                        $check1 = $this->admin->where('admin_key', $admin_key)
                                              ->where('attempt', '>', 3)
                                              ->exists();
                        return $query->when($check1, function ($query) use ($request,$admin_key) {
                            $this->admin->where('admin_key', $admin_key)
                                        ->update(['block' => 1,'attempt'=>0]);
                            $request->session()->flash('error', 'You have attempted more than 3 times and now you are blocked, contact admin to unblock');
                            return [];
                        });
                    })->exists()) {
                    if (!(Session::has('error'))) {
                        $request->session()->flash('error', 'You are blocked');
                    }
                    return redirect()->back();
                } else {
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'is_admin'=>IS])) {
                        $this->admin->where('admin_key', $admin_key)->update(['attempt' => 0]);
                        return redirect()->route('scubaya::admin::dashboard');
                    } else {
                        $this->admin->where('admin_key', $admin_key)->increment('attempt');
                        $request->session()->flash('error', 'Username or Password is incorrect');
                        return redirect()->back();
                    }
                }
            }

            $request->session()->flash('error', 'Username or Password is incorrect');
            return redirect()->back();
        }
        return view('admin.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('scubaya::admin::login');
    }

}
