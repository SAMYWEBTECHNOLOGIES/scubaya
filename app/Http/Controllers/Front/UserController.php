<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Crypt;
use App\Scubaya\model\EmailTemplate;

class UserController extends Controller
{
    /**
     * register user from front
     */
    public function register(Request $request)
    {
        $this->validate($request,[
            'first_name'    =>  'required',
            'last_name'     =>  'required',
            'email'         =>  'required',
            'password'      =>  'required|confirmed'
        ]);

        $all_user_emails    =   User::where('is_user',IS)->get(['email']);

        $check              =   $all_user_emails->first(function ($value,$key) use ($request){
            return Crypt::decrypt($value->email) == $request->email;
        });

        if($check){
            return redirect()->back()->withErrors(['Email already exists, Try to login'])->withInput();
        }

        $data       =   [
            'first_name'        =>  Crypt::encrypt($request->first_name),
            'last_name'         =>  Crypt::encrypt($request->last_name),
            'email'             =>  Crypt::encrypt($request->email),
            'UID'               =>  User::userId(),
            'password'          =>  bcrypt($request->password),
            'is_user'           =>  IS,
            'account_status'    =>  USER_STATUS_PENDING,
            'confirmation_code' =>  str_random(30),
        ];
        $user   =   User::saveUser($data);

        if($email = EmailTemplate::getTemplateByAction('user', 'user_account_verification')) {
            $user->verification_url =   route('scubaya::user::verify_user', [$user->id,$user->confirmation_code]);
            $email->setData($user);

            (new SendMailHelper($email))->send();
        }

        //$this->sendConfirmationMail($request->email,route('scubaya::user::verify_user', [$user->id,$user->confirmation_code]));

        return redirect(urldecode($request->get('redirect')).'?'.http_build_query([
                'error' =>  'Verify your email and then login here.'
        ]));
    }

    public function login(Request $request)
    {
        $loginController    =   new \App\Http\Controllers\User\LoginController();
        return $loginController->login($request);
    }
}
