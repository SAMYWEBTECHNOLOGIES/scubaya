<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class MerchantPolicies extends Model
{
    protected $table        =   'merchant_policies';

    public static function savePolicy($data)
    {
        $merchant_policy     =   new MerchantPolicies();
        foreach($data as $key=> $value){
            $merchant_policy->$key  =   $value;
        }

        $merchant_policy->save();
    }
}
