<?php

namespace App\Console\Commands;

use App\Scubaya\model\Currency;
use App\Scubaya\model\CurrencyExchange;
use App\Scubaya\model\GlobalSetting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubaya:exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes an api call to get the exchange rate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(GlobalSetting::where('name','like','api.currency.priority_list')->exists()){
            /*get the api */
            $exchange_api        =   GlobalSetting::where('name','like','api.currency.priority_list')->pluck('value','name')->first();

            /*make the associate array to call the api's accordingly*/
            $api_calls   =   [
                'api.fixer.io'           =>  'runFixerApi',
                'currencylayer.net'      =>  'runCurrencyLayerApi',
                'xe.com'                 =>  'runXeApi',
            ];

            /*call the  api*/
            $this->{$api_calls[$exchange_api]}();
        }
        else {
            $this->info('Please set the currency priority first');
        }
    }


    public function runFixerApi()
    {
        $currencies         =   Currency::all();
        foreach($currencies as $currency){
            $client         =   new Client();
            /*set the query string by setting base*/
            $fixer          =    $client->get('http://data.fixer.io/api/latest',
                [
                    'query'    =>  [
                        'access_key'    =>  GlobalSetting::where('name','api.currency.fixer_key')->value('value'),
                        'source'        =>  $currency->name
                    ]
                ]);

            /*if status code is 200 or success*/
            if($fixer->getStatusCode() == 200) {
                $data = json_decode($fixer->getBody());
                $base_currency = $data->base;
                /*delete the old currency rates*/
//            CurrencyExchange::deleteOldBaseCurrencyData($base_currency);
                /*dump the data in the currency exchange table*/
                foreach ($data->rates as $exchange_currency => $exchange_rate) {
                    $currencyExchange                   = new CurrencyExchange();
                    $currencyExchange->currency_from    = $base_currency;
                    $currencyExchange->currency_to      = $exchange_currency;
                    $currencyExchange->value            = $exchange_rate;
                    $currencyExchange->save();
                }
            $this->info('Exchange rates updated with base currency '.$currency->name.' with help of api.fixer.io on '.Carbon::now());
            }else return false;
        }return true;
    }

    public function runCurrencyLayerApi()
    {
        $currencies         =   Currency::all();
        foreach($currencies as $currency){
            $client             =   new Client();
            $currency_layer     =   $client->get('http://apilayer.net/api/live',
                [
                    'query'    =>  [
                        'access_key'    =>  GlobalSetting::where('name','api.currency.currency_layer_key')->value('value'),
                        'currencies'    =>  $currency->name
                    ]

                ]);
            /*if status code is success or 200*/
            if($currency_layer->getStatusCode() == 200){
                $data           =   json_decode($currency_layer->getBody());
                $base_currency  =   $data->source;
                /*delete the old currency rates*/
//            CurrencyExchange::deleteOldBaseCurrencyData($base_currency);

                /*dump the data in the currency exchange table*/
                foreach($data->quotes as $exchange_currency=>$exchange_rate){
                    $currencyExchange                    =   new CurrencyExchange();
                    $currencyExchange->currency_from     =   $base_currency;
                    $currencyExchange->currency_to       =   substr($exchange_currency,strlen($base_currency));
                    $currencyExchange->value             =   $exchange_rate;
                    $currencyExchange->save();
                }
                $this->info('Exchange rates updated with base currency '.$currency->name.' help of apilayer.net on '.Carbon::now());
            }else return false;
        }return true;
    }

    public function runXeApi()
    {
        return true;
    }
}
