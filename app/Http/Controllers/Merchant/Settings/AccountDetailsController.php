<?php

namespace App\Http\Controllers\Merchant\Settings;

use App\Scubaya\model\User;
use App\Scubaya\model\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function saveMerchantDetails(Request $request,$merchantKey)
    {
        if($request->isMethod('post')) {

            $merchant = Merchant::where('merchant_key', '=', $merchantKey)->first();

            $merchant->company_name         = $request->company_name;
            $merchant->vat_number           = $request->vat_number;
            $merchant->chamber_of_commerce  = $request->chamber_of_commerce;
            $merchant->street               = $request->street;
            $merchant->town                 = $request->town;
            $merchant->region               = $request->region;
            $merchant->country              = $request->country;
            $merchant->postcode             = $request->postcode;
            $merchant->telephone            = $request->telephone;
            $merchant->longitude            = $request->longitude;
            $merchant->latitude             = $request->latitude;

            $merchant->update();
            $request->session()->flash('success','Updated Successfully.');
            return redirect()->route('scubaya::merchant::settings::account_details', [$merchantKey]);
        }


        $accountDetails = Merchant::where('merchant_key',$merchantKey)->first();

        return view('merchant.settings.account_details.account_details')
                    ->with('accountDetail', $accountDetails);
    }
}
