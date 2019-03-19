<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Shops extends Model
{
    use Notifiable;

    protected $table    =   'shop_information';

    public static function saveShop($data)
    {
        $shop   =   new Shops();

        foreach($data as $key => $value){
            $shop->$key =   $value;
        }
        $shop->save();

        return $shop;
    }

    public static function updateShop($id, $data)
    {
        $shop   =   Shops::find($id);

        foreach($data as $key => $value){
            $shop->$key =   $value;
        }

        $shop->update();

        return $shop;
    }
}
