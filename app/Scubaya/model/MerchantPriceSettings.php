<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class MerchantPriceSettings extends Model
{
    protected $table    = 'merchant_pricing_settings';

    protected $fillable = ['merchant_key', 'active_commission', 'charge_commission_merchant',
            'charge_commission', 'auto_block', 'website_level', 'unpaid_invoices', 'charge_commission_shop', 'commission_dive_center', 'commission_dive_hotel', 'commission_dive_shop'
    ,'commission_percentage'];

}
