<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request)
    {
        return view('merchant.auth.passwords.reset')->with(
            ['token' => $request->token]
        );
    }

    public function reset(Request $request)
    {
        $user   =   User::where('email', $request->get('email'))
                        ->where('ise_user', IS)
                        ->first();

        if($user) {
            if($user->token ==  $request->get('token')) {

                $this->validate($request, [
                    'password'  =>  'required|confirmed'
                ]);

                $user->password =   Hash::make($request->get('password'));
                $user->update();

                return redirect()->route('scubaya::user::login');
            }

            return back()->with('token_invalid', 'Your token is invalid!');
        }

        return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
    }
}
