<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RoomFeatures extends Model
{
    use Notifiable;

    protected $table    =   'room_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feature_description','icon',
    ];

    public static function saveFeature($data)
    {
        $feature   =   new RoomFeatures();

        foreach($data as $key => $value){
            $feature->$key =   $value;
        }
        $feature->save();
        return $feature;
    }

    public static function updateFeature($id, $data)
    {
        $feature   =   RoomFeatures::find($id);

        foreach($data as $key => $value){
            $feature->$key =   $value;
        }
        $feature->update();
        return $feature;
    }
}
