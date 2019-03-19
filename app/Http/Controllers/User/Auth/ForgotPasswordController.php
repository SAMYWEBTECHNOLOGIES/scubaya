<?php

namespace App\Http\Controllers\User\Auth;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\EmailTemplate;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('user.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $users   =   User::where('is_user', IS)->get();

        $user    =   $users->first(function ($value, $key) use ($request) {
            return User::decryptString($value->email) == $request->email;
        });

        if($user) {
            $token          =   Str::random(64);
            $user->token    =   $token;
            $user->update();

            // send a password reset email to user
            if($email   =   EmailTemplate::getTemplateByAction('merchant', 'password_reset')) {

                $data   =   new \stdClass();

                $data->password_reset_url   =   route('scubaya::user::password_reset_token', [$token]);
                $data->email                =   $request->email;
                $email->setData($data);

                (new SendMailHelper($email))->send();
            }

            return back()->with('status', 'We have e-mailed you the password reset link!');
        }

        return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
    }
}
