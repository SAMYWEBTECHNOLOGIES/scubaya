<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $fillable =   ['active','name','latitude','longitude','type','need_a_boat','level','image'];

    public static function saveLocation($data)
    {
        $location   =   new Locations();

        foreach($data as $key => $value ) {
            $location->$key     =   $value;
        }

        $location->save();
        return $location;
    }
}
