<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RoomTypes extends Model
{
    use Notifiable;

    protected $table    =   'room_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_type','icon',
    ];

    public static function saveRoomType($data)
    {
        $roomType   =   new RoomTypes();

        foreach($data as $key => $value){
            $roomType->$key =   $value;
        }
        $roomType->save();
        return $roomType;
    }

    public static function updateRoomType($id, $data)
    {
        $feature   =   RoomTypes::find($id);

        foreach($data as $key => $value){
            $feature->$key =   $value;
        }
        $feature->update();
        return $feature;
    }
}
