<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    protected $fillable     =   ['user_key','distance','weight','pressure','temperature','volume','date_format'
            ,'time_format','coordinates_format','language','currency','departure_airport','newsletter','partners_related_offers'
    ];

    public static function saveUserPreferences($data)
    {
        $user_preferences   =   new UserPreferences();
        foreach ($data as $key=>$value){
            $user_preferences->$key =   $value;
        }
        $user_preferences->save();
        return $user_preferences;
    }


}
