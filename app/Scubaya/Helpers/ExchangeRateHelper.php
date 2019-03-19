<?php
namespace App\Scubaya\Helpers;

use App\Scubaya\model\CurrencyExchange;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\RoomPricingSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExchangeRateHelper
{
    private $ipAddress;

    private $defaultExchangeRate = 1;

    private $defaultCurrencyFrom = 'EUR';

    private $exchangeRate;

    private $currencySymbols;

    public function __construct($ipAddress, $merchantPrimaryIds)
    {
        $this->ipAddress       =   $ipAddress;
        $this->merchantIds     =   $merchantPrimaryIds;
        $this->currencySymbols =   config('currency-symbols.symbols');
    }

    public function getExchangeRate()
    {
        $clientGeoInfo  =   geoip($this->ipAddress);

        if($clientGeoInfo){

            $currencyTo      =   $clientGeoInfo['currency'];
            $date            =   Carbon::now()->format('Y-m-d');

            /* TODO: get from merchant pricing settings */
            foreach($this->merchantIds as $merchantId){
                $mid            =   isset($merchantId->merchant_primary_id) ? $merchantId->merchant_primary_id : $merchantId;
                $settings       =   json_decode(RoomPricingSettings::where('merchant_primary_id', $mid)->value('currency'));

                $currencyFrom   =   empty($settings->mcurrency) ? $this->defaultCurrencyFrom : $settings->mcurrency;


                // If user and merchant currencies are same
                // then no need to apply conversion else
                // fetch exchange rate form currency table

                if($currencyFrom != $currencyTo){
                    $rate   =   CurrencyExchange::where('currency_from', $currencyFrom)
                                ->where('currency_to', $currencyTo)
                                ->where('created_at', 'like', $date.'%')
                                ->first();

                    if($rate && count($rate) > 0){
                        $this->exchangeRate[$mid]   =   [
                            'rate'      =>  $rate->value,
                            'symbol'    =>  $this->currencySymbols[$currencyTo],
                            'currency'  =>  $currencyTo
                        ];
                    }else{
                        $this->exchangeRate[$mid]   =   [
                            'rate'      =>  $this->defaultExchangeRate,
                            'symbol'    =>  $this->currencySymbols['EUR'],
                            'currency'  =>  'EUR',
                        ];
                    }
                } else {
                    $this->exchangeRate[$mid]   =   [
                        'rate'      =>  $this->defaultExchangeRate,
                        'symbol'    =>  $this->currencySymbols[$currencyTo],
                        'currency'  =>  $currencyTo,
                    ];
                }
            }

            return $this->exchangeRate ;
        }

        return $this->defaultExchangeRate;
    }
}