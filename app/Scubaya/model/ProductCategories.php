<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    //
    protected $fillable     =   ['active','name','parent_id','menu_ids','merchant_key'];

    public static function addProductCategories($data)
    {
        $product_category      =   new ProductCategories();

        foreach($data as $key=>$value){
            $product_category->$key      =   $value;
        }

        $product_category->save();

        return $product_category;
    }
}
