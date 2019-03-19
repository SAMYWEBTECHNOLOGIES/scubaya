<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class MerchantProductInvoicesMapper extends Model
{
    protected $fillable =   ['merchant_key','invoice_id','product_id'];
    protected $table    =   'merchant_x_product_invoices';

    public static function saveProductInvoiceMapper($data)
    {
        $merchant_product_invoice_mapper    =   new MerchantProductInvoicesMapper();
        foreach ($data as $key=>$value){
            $merchant_product_invoice_mapper->$key  =   $value;
        }
        $merchant_product_invoice_mapper->save();
    }
}
