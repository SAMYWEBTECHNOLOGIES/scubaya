<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class BoatTypes extends Model
{
    protected $fillable     =   [
        'active','name','image'];
    public static function addBoatTypes($data)
    {
        $boat_type      =   new BoatTypes();
        print_r($boat_type);
        foreach($data as $key=>$value){
            $boat_type->$key      =   $value;
        }
        $boat_type->save();
        return $boat_type;
    }

}
