<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Rooms extends Model
{
    use Notifiable;

    protected $table    =   'room_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','max_people'
    ];

    public static function saveRoom($data)
    {
        $room   =   new Rooms();

        foreach($data as $key => $value){
            $room->$key =   $value;
        }
        $room->save();
        return $room;
    }

    public static function updateRoom($id, $data)
    {
        $room   =   Rooms::find($id);

        foreach($data as $key => $value){
            $room->$key =   $value;
        }
        $room->update();
        return $room;
    }
}
