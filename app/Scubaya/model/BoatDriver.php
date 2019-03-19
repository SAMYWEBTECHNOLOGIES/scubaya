<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BoatDriver extends Model
{
    use Notifiable;

    protected $table        =   'boat_drivers';

    public static function saveBoatDriver($data)
    {
        $driver  =   new BoatDriver();

        foreach($data as $key => $value){
            $driver->$key    =   $value;
        }
        $driver->save();

        return $driver;
    }

    public static function updateBoatDriver($id, $data)
    {
        $driver   =   BoatDriver::find($id);

        foreach($data as $key => $value){
            $driver->$key =   $value;
        }
        $driver->update();

        return $driver;
    }
}
