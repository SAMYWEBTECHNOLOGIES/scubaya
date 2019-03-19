<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class MerchantDiveCenterInvoicesMapper extends Model
{
    protected $fillable =   ['merchant_key','invoices_id','course_id'];
    protected $table    =   'merchant_x_dive_center_invoices';

    public static function saveMerchantDiveCenterMapper($data)
    {
        $merchant_dive_center_invoice_mapper    =   new MerchantDiveCenterInvoicesMapper();
        foreach ($data as $key=>$value){
            $merchant_dive_center_invoice_mapper->$key  =   $value;
        }
        $merchant_dive_center_invoice_mapper->save();
        return $merchant_dive_center_invoice_mapper;
    }
}
