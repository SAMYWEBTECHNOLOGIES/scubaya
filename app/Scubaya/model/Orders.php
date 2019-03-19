<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $table    =   'orders';

    public static function saveOrders($data)
    {
        $orders   =   new Orders();

        foreach ($data as $key => $value) {
            $orders->$key     =   $value;
        }

        $orders->save();

        //return $orders;
    }
}
