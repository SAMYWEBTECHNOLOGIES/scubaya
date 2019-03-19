<?php

namespace App\Http\Controllers\Merchant\Settings;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\TaxRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TaxRateController extends Controller
{
    private $authUserId;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });
    }

    public function index()
    {
        $taxRates    =   TaxRate::where('merchant_key', $this->authUserId)->get();

        return view('merchant.settings.tax_rate.index')
            ->with('taxRates', $taxRates)
            ->with('sno', 1);
    }

    protected function _prepareData($request)
    {
        $taxRate    =   new \stdClass();

        $taxRate->title     =   $request->get('title');
        $taxRate->country   =   json_encode([
            'name'      =>  $request->get('country'),
            'iso_code2' =>  $request->get('country_code')
        ]);
        $taxRate->state     =   $request->get('state');
        $taxRate->city      =   $request->get('city');
        $taxRate->region    =   $request->get('region');
        $taxRate->zipcode   =   $request->get('zipcode');
        $taxRate->rate      =   $request->get('rate');

        return $taxRate;
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $this->validate($request, [
                'title'     =>  'required',
                'country'   =>  'required',
                'rate'      =>  'required'
            ]);

            $taxRate                =   $this->_prepareData($request);
            $taxRate->merchant_key  =   $this->authUserId;

            TaxRate::saveTaxRate($taxRate);

            return Redirect::to(route('scubaya::merchant::settings::tax_rates', [Auth::id()]));
        }

        return view('merchant.settings.tax_rate.create');
    }

    public function edit(Request $request)
    {
        if($request->isMethod('post')) {
            $this->validate($request, [
                'title'     =>  'required',
                'country'   =>  'required',
                'rate'      =>  'required'
            ]);

            TaxRate::updateTaxRate($request->tax_rate_id, $this->_prepareData($request));

            return Redirect::to(route('scubaya::merchant::settings::tax_rates', [Auth::id()]));
        }

        $taxRate    =   TaxRate::find($request->tax_rate_id);

        return view('merchant.settings.tax_rate.edit')->with('taxRate', $taxRate);
    }

    public function delete(Request $request)
    {
        TaxRate::destroy($request->tax_rate_id);

        return Redirect::to(route('scubaya::merchant::settings::tax_rates', [Auth::id()]));
    }
}
