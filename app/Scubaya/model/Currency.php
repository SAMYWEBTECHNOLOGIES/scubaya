<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table    =   'currencies';

    public static function saveCurrency($data)
    {
        $currency     =   new Currency();
        foreach($data as $key=> $value){
            $currency->$key  =   $value;
        }

        $currency->save();
    }
}
