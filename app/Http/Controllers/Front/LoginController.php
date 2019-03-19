<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\EmailTemplate;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    protected $message  =   '';

    public function index()
    {
        return view('front.registration.index');
    }

    public function showMerchantSignUpForm()
    {
        return view('front.registration.merchant.sign_up');
    }

    public function showInstructorSignUpForm()
    {
        return view('front.registration.instructor.sign_up_instructor');
    }

    public function prepareData($request)
    {
        // generate confirmation code
        $confirmation_code = str_random(30);

        $merchantData   =   [
            'UID'               =>  Merchant::merchantId(),
            'first_name'        =>  $request->get('first_name'),
            'last_name'         =>  $request->get('last_name'),
            'email'             =>  $request->get('merchant_email'),
            'password'          =>  Hash::make($request->get('merchant_password')),
            'is_merchant'       =>  IS,
            'account_status'    =>  MERCHANT_STATUS_NEW,
            'confirmation_code' =>  $confirmation_code
        ];
        return $merchantData;
    }

    public function _prepareMerchantDetails($merchantId, $roleId)
    {
        $merchant       =   new \stdClass();

        $merchant->merchant_key =   $merchantId;
        $merchant->role_id      =   $roleId;

        return $merchant;
    }

    public function createMerchant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_email' => 'required|email|unique:users,email,NULL,id,is_merchant,'.IS,
        ]);

        if ($validator->fails()){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // save merchant
        $merchantData   =   $this->prepareData($request);
        $user           =   User::saveUser($merchantData);

        // save merchant profile details
        Merchant::saveMerchant($this->_prepareMerchantDetails($user->id, MERCHANT));

        // send a verification email to registered user
        if($email   =   EmailTemplate::getTemplateByAction('merchant', 'merchant_account_verification')) {
            $merchantData['confirmation_url']   =   route('scubaya::register::verify', [$merchantData['confirmation_code']]);
            $email                              =   $email->setData((object)$merchantData);
            (new SendMailHelper($email))->send();
        }

        return redirect()->route('scubaya::register::success');
    }

    public function showSuccess()
    {
        return view('front.registration.success');
    }

    public function verifyEmail($confirmation_code)
    {
        $user   =   User::getUserByConfirmationCode($confirmation_code);

        if(!$user)
        {
            $this->message  =   'Confirmation code is invalid.';
        }
        else
        {
            if($user->confirmed == 1)
            {
                    $this->message  =   'Your account is already activated.Please try to sign in';
            }
            else
            {
                $this->message              =   'You have successfully verified your account.';
                $user->confirmed            =   1;
                $user->account_status       =   MERCHANT_STATUS_PENDING;
                $user->save();
            }
        }

        Session::flash('verification_message', $this->message);
        return redirect()->route('scubaya::merchant::index');
    }

    public function createInstructor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_email' => 'required|unique:merchant.merchants,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // create merchant
        $merchantData   =   $this->prepareInstructorData($request);
        Merchant::saveMerchant($merchantData);

        // send a verification email to registered user
        /*$this->sendVerificationEmail($merchantData);*/

        return redirect()->route('scubaya::register::success');
    }

    public function prepareInstructorData($request)
    {
        // generate confirmation code
        $confirmation_code = str_random(30);

        $instructorData   =   [
            'UID'               =>  User::userId(),
            'first_name'        =>  $request->get('first_name'),
            'last_name'         =>  $request->get('last_name'),
            'email'             =>  $request->get('instructor_email'),
            'password'          =>  bcrypt($request->get('instructor_password')),
            'is_merchant_user'  =>  IS,
            'rating'            =>  null ,
            'screening'         =>  null ,
            'account_status'    =>  MERCHANT_STATUS_NEW,
            'confirmation_code' =>  $confirmation_code
        ];

        return $instructorData;
    }
}
