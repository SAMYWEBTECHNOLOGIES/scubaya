<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\model\Currency;
use App\Scubaya\model\GlobalSetting;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\RoomPricingSettings;
use App\Scubaya\model\TaxRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PricingSettingsController extends Controller
{
    private $defaultValues;

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
        $settings   =   (array)json_decode(RoomPricingSettings::where('merchant_primary_id', $this->authUserId)->first());

        $this->_setTariffDefaultValues();
        $currencies =   Currency::all();

        return view('merchant.settings.pricing_settings.index')
             ->with('settings', count($settings) ? json_decode($settings['currency']) : null)
             ->with('defaultValues', $this->defaultValues)
             ->with('currencies', $currencies);
    }

    protected function _setTariffDefaultValues()
    {
        $settings   =   (array)json_decode(RoomPricingSettings::where('merchant_primary_id', $this->authUserId)->first());

        if(count($settings) > 0)
        {
            $settings       =   json_decode($settings['currency']);

            if(!empty($settings->micromanage)){
                $settings       =   json_decode($settings->micromanage);

                $this->defaultValues    =   [
                    'default_price'         =>  $settings->default_price,
                    'default_min_nights'    =>  $settings->default_min_nights,
                    'default_years'         =>  $settings->years_to_show
                ];
            }
        }
        else
        {
            /* fetch from global config set by admin */
            $settings   =   GlobalSetting::where('name', 'like','merchant.hotel_accomodation%')->pluck('value', 'name')->toArray();

            if(count($settings) > 0){
                $this->defaultValues    =   [
                    'default_price'         =>  $settings['merchant.hotel_accomodation.tariff_default_price'],
                    'default_min_nights'    =>  $settings['merchant.hotel_accomodation.tariff_default_min_nights'],
                    'default_years'         =>  $settings['merchant.hotel_accomodation.default_years_to_show']
                ];
            }
        }
    }

    protected function _prepareData($request)
    {
        $settings       =   array();

        $currency       =   [
            'mcurrency'                     =>  $request->get('tariff_currency'),
            'currency_format'               =>  $request->get('currency_format'),
            'tariff_mode'                   =>  $request->get('tariff_mode'),
            'tariff_model'                  =>  $request->get('tariff_model'),
            'tax_rate'                      =>  $request->get('tax_rate'),
            'is_tax_percentage'             =>  $request->get('is_tax_percentage'),
            'prices_gross'                  =>  $request->get('prices_gross'),
            'prices_pppn'                   =>  $request->get('prices_pppn'),
            'tourist_tax_rate'              =>  $request->get('tourist_tax_rate'),
            'is_tourist_rate_percentage'    =>  $request->get('is_tourist_rate_percentage')
        ];

        if($request->get('tariff_mode') == 'micro'){
            $currency['micromanage']    =   json_encode([
                'default_price'         =>  $request->get('tariff_default_price'),
                'default_min_nights'    =>  $request->get('tariff_default_min_nights'),
                'years_to_show'         =>  $request->get('default_years_to_show')
            ]);
        }

        $settings['merchant_primary_id']  =   $this->authUserId;
        $settings['currency']             =   json_encode($currency);

        return $settings;
    }

    public function savePricingSettings(Request $request)
    {
        $settings   =   $this->_prepareData($request);

        RoomPricingSettings::updateOrCreate(['merchant_primary_id' => $settings['merchant_primary_id']], $settings);

        return Redirect::to(route('scubaya::merchant::pricing_settings', [Auth::id()]));
    }
}
