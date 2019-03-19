<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\Currency;
use App\Scubaya\model\CurrencyExchange;
use App\Scubaya\model\GlobalSetting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /*to set the global settings of merchants or user*/
    public function hotelAccommodation(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validate($request,[
                'tariff_default_price'          =>  'required',
                'tariff_default_min_nights'     =>  'required',
                'default_years_to_show'         =>  'required',
            ]);

            /*unsetting the variables*/
            $data       =   $request->all();
            unset($data['_token']);

            /*saving the settings with prefix1 and prefix2 */
            GlobalSetting::saveGlobalSettings($data,'merchant','hotel_accomodation');
            $request->session()->flash('success','Settings saved successfully.');

            return redirect()->route('scubaya::admin::global_settings::merchants::hotel_accommodation');
        }

        $global_settings        =       GlobalSetting::where('name','like','merchant.hotel_accomodation%')->pluck('value','name')->toArray();
        return view('admin.global_settings.merchants.hotel_accomodation',['global_settings'=>$global_settings]);
    }

    protected function currency(Request $request)
    {
        /*saving the currency settings*/
        if($request->isMethod('post')){
            $this->validate($request,[
               //'api_priority'               =>  'required',
               //'cron_job'                   =>  'required',
               //'key_currency_layer'         =>  'required',
               //'key_xe'                     =>  'required',
            ]);

            $data   =   [
                'api.currency.priority_list'        =>    $request->exchange_api,
                'api.currency.job'                  =>    $request->cron_job,
                'api.currency.currency_layer_key'   =>    $request->currency_layer_key ? $request->currency_layer_key : '' ,
                'api.currency.xe_key'               =>    $request->xe_key ? $request->xe_key : '',
                'api.currency.fixer_key'            =>    $request->fixer_key ? $request->fixer_key : '',
            ];

            GlobalSetting::saveApiSettings($data);

            $request->session()->flash('success','Settings saved successfully');
            return redirect()->route('scubaya::admin::currency_settings');
        }

        $currency_settings        =       GlobalSetting::where('name','like','api.currency%')->pluck('value','name')->toArray();
        return view('admin.currency_settings.index',['currency_settings'=>$currency_settings]);
    }
}
