<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class MarineLife extends Model
{
    protected $fillable     =   [
        'active','common_name','scientific_name','main_image','max_images','short_description','long_description','dive_description','tourist_description'    ];
    public static function addMarineLife($data)
    {
        $marine_life     =   new MarineLife();
        foreach($data as $key=>$value){
            $marine_life->$key      =   $value;
        }
        $marine_life->save();
        return $marine_life;
    }
}
