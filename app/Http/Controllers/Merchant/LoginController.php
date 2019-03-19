<?php

namespace App\Http\Controllers\Merchant;

use App\Events\Login;
use App\Listeners\LogSuccessfulLogin;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /* Render the login page */
    public function index(){
        return redirect()->route('scubaya::merchant::login');
    }

    /*
     * This function validate merchant with email,
     * password and account confirmation to login
     * into the system
     */
    public function login(Request $request)
    {
        if($request->isMethod('login_merchant')){

            $merchant = User::where('id',$request->merchant_key)->first();
            Auth::loginUsingId($request->merchant_key);

            event(new Login($merchant, $request->ip()));

            if(Auth::user()->is_merchant){
                /* check for the account verification warning message */
                $showWarning  =   MerchantDetails::where('merchant_primary_id', $merchant->id)->get();

                return redirect()->route('scubaya::merchant::dashboard', [$merchant->id])
                    ->with('popup_merchant', count($showWarning) > 0 ? false : true);
            }

            if(Auth::user()->is_merchant_user){
                return redirect()->route('scubaya::merchant::dashboard', [$merchant->id]);
            }

        }

        if($request->isMethod('post')){
            $this->validate($request, [
                'merchant_email'    => 'required',
                'merchant_password' => 'required',
            ]);

            $merchants  = User::where('is_merchant', IS)
                            ->orWhere('is_merchant_user', IS)
                            ->get();

            $merchant   = $merchants->first(function ($value, $key) use ($request) {
                return User::decryptString($value->email) == $request->get('merchant_email');
            });

            if($merchant){

                if(is_null($merchant->password)) {

                    Session::flash('status', 'Please Verify your role to login!');
                    return Redirect::back();

                } else {

                    if( !(Hash::check($request->get('merchant_password'), $merchant->password) )) {
                        Session::flash('status', 'Invalid Password!');
                        return Redirect::back();
                    }
                }

            } else {

                Session::flash('status', 'Invalid Email Or Password!');
                return Redirect::back();

            }

            if( $merchant->is_merchant ) {
                if( ! $merchant->confirmed) {
                    Session::flash('status', 'You need to confirm your account. We have sent you an activation link, please check your email.');
                    return Redirect::back();
                }

                /* to check merchant account is disable or not */
                if(MerchantDetails::isMerchantAccountDisabled($merchant->id)) {
                    Session::flash('status', 'Your account is currently disabled, please contact us for more information.');
                    return Redirect::back();
                }

                Auth::attempt(['email' => $request->merchant_email, 'password' => $request->merchant_password,'is_merchant'=>IS]);

                if( ! Auth::check() ) {
                    Session::flash('status', 'You are not authorized to login.');
                    return Redirect::back();
                }
            }

            if( $merchant->is_merchant_user ) {

                /* to check merchant account is disable or not */
                if(MerchantDetails::isMerchantAccountDisabled($merchant->id)) {
                    Session::flash('status', 'Your account is currently disabled, please contact us for more information.');
                    return Redirect::back();
                }

                if(MerchantUsersRoles::verifyRole($merchant->id)) {
                    Auth::attempt([
                        'email'             => $merchant->email,
                        'password'          => $request->merchant_password,
                        'is_merchant_user'  => IS
                    ]);
                } else {
                    Session::flash('status', "You need to verify your role. To verify it please login here: <a href='".route('scubaya::user::login')."'>Login here</a>");
                    return Redirect::back();
                }

                if( ! Auth::check() ) {
                    Session::flash('status', 'You need to confirm your account. We have sent you an activation link, please check your email.');
                    return Redirect::back();
                }
            }

            /* Fire an event to save the last login data of user */
            event(new Login($merchant, $request->ip()));

            if(Auth::user()->is_merchant){
                /* check for the account verification warning message */
                $showWarning  =   MerchantDetails::where('merchant_primary_id', $merchant->id)->get();

                return redirect()->route('scubaya::merchant::dashboard', [$merchant->id])
                    ->with('popup_merchant', count($showWarning) > 0 ? false : true);
            }

            if(Auth::user()->is_merchant_user){
                return redirect()->route('scubaya::merchant::dashboard', [$merchant->id]);
            }
        }

        return view('merchant.login.login');
    }
}
