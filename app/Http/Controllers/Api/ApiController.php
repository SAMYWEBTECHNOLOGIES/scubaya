<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Front\LoginController;
use App\Scubaya\model\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /* Authenticate incoming request whether
    it is from the correct(white list domain) domain or not */
    public function authenticateRequest(Request $request)
    {
        $domain             =   $request->getHttpHost();
        $whiteListDomain    =   config('scubaya.whitelist_domain');

        return in_array($domain, $whiteListDomain);
    }

    /* It will create merchant account only if it is authenticated */
    public function createMerchantAccount(Request $request)
    {
        // create merchant when request is authenticated else return message
        if($this->authenticateRequest($request)){
            $validator = Validator::make($request->all(), [
                'first_name'    => 'required',
                'last_name'     => 'required',
                'email'         => 'required|unique:merchants',
                'password'      => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message'   =>  $validator->errors()
                ]);
            }

            // create Merchant
            $merchant       =   new LoginController();
            $merchantData   =   $merchant->prepareData($request);
            Merchant::saveMerchant($merchantData);

            // send verification email to registered user
            $merchant->sendVerificationEmail($merchantData);

            return response()->json([
                'message'   =>  'You account has been created successfully.'
            ]);
        }

        return response()->json([
            'message'   =>  'You request is not authorized.'
        ]);
    }
}
