<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class CurrencyExchange extends Model
{
    protected $table    =   'currency_conversion';

    public static function deleteOldBaseCurrencyData($base_currency)
    {
        self::where('currency_from',$base_currency)->delete();
    }
}
