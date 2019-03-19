<?php

namespace App\Http\Controllers\User;

use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',['except'=>['logout']]);
    }

    public function login(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $all_users = User::where('is_user', IS)->get();

            $user = $all_users->first(function ($value, $key) use ($request) {
                return User::decryptString($value->email) == $request->email;
            });

            if ($user) {
                if ($user->confirmed) {
                    if (Hash::check($request->password,$user->password)){
                        /*Login user*/
                        Auth::login($user);
                        if ($request->get('redirect')) {
                            setcookie('scubaya_dive_in', Crypt::encrypt(Auth::id()), time() + 7200, '/', env('APP_URL'));
                            return redirect(urldecode($request->get('redirect')));
                        }
                        return redirect()->intended('scubaya::user::dashboard');
                    }
                    if ($request->get('redirect')) {
                        return redirect(urldecode($request->get('redirect')) . '?' . http_build_query([
                                'error' => 'Username or Password is incorrect'
                        ]));
                    }
                    return redirect()->back();
                }

                if ($request->get('redirect')) {
                    return redirect()->intended(urldecode($request->get('redirect')) . '?' . http_build_query([
                            'error' => 'Please check your email to verify your account'
                    ]));
                }

                $request->session()->flash('error', 'Please check your email to verify your account');
                return redirect(route('scubaya::user::login'));

            } else {
                if ($request->get('redirect')) {
                    return redirect(urldecode($request->get('redirect')) . '?' . http_build_query([
                            'error' => 'User Doesn\'t Exist'
                        ]));
                }
                $request->session()->flash('error', 'User Doesn\'t Exist');
                return redirect(route('scubaya::user::login'));
            }
        }
        return view('user.login');
    }

    public function logout()
    {
        Auth::logout();
        /*unset the cookie for the logged in user*/
        Cookie::queue('scubaya_dive_in',null, -1,'/', env('APP_URL'));
        return redirect()->route('scubaya::user::login');
    }
}
