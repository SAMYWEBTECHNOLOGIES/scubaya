<?php

namespace App\Http\Controllers\Merchant\Auth;

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
        return view('merchant.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user   =   User::where('email', $request->email)
                        ->where('is_merchant', IS)
                        ->orWhere('is_merchant_user', IS)
                        ->first();

        if($user) {
            $token          =   Str::random(64);
            $user->token    =   $token;
            $user->update();

            // send a password reset email to user
            if($email   =   EmailTemplate::getTemplateByAction('merchant', 'password_reset')) {

                $data   =   new \stdClass();

                $data->password_reset_url   =   route('scubaya::merchant::password_reset_token', [$token]);
                $data->email                =   $request->email;
                $email                      =   $email->setData($data);

                (new SendMailHelper($email))->send();
            }

            return back()->with('status', 'We have e-mailed you the password reset link!');
        }

        return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
    }
}
