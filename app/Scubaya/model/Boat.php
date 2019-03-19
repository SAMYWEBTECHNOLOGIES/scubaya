<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Boat extends Model
{
    use Notifiable;

    protected $table        =   'boats';

    public static function saveBoats($data)
    {
        $boat  =   new Boat();

        foreach($data as $key => $value){
            $boat->$key    =   $value;
        }
        $boat->save();

        return $boat;
    }

    public static function updateBoats($id, $data)
    {
        $boat   =   Boat::find($id);

        foreach($data as $key => $value){
            $boat->$key =   $value;
        }
        $boat->update();

        return $boat;
    }
}
