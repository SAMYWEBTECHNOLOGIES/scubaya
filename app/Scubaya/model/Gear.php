<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Gear extends Model
{
    use Notifiable;

    protected $table    =   'gears';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon', 'category'];

    public static function saveGear($data)
    {
        $gear  =   new Gear();

        foreach($data as $key => $value){
            $gear->$key     =   $value;
        }

        $gear->save();

        return $gear;
    }

    public static function updateGear($id, $data)
    {
        $gear  =   Gear::findOrFail($id);

        foreach($data as $key => $value){
            $gear->$key     =   $value;
        }

        $gear->update();

        return $gear;
    }
}
