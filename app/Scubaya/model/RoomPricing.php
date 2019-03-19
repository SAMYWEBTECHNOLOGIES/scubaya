<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RoomPricing extends Model
{
    use Notifiable;

    protected $table    =   'room_pricing';

    public static function savePricing($data)
    {
        $pricing   =   new RoomPricing();

        foreach($data as $key => $value){
            $pricing->$key =   $value;
        }
        $pricing->save();
    }

    public static function updatePricing($id, $data)
    {
        $pricing   =  RoomPricing::find($id);

        foreach($data as $key => $value){
            $pricing->$key =   $value;
        }
        $pricing->update();
    }
}
