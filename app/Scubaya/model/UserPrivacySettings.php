<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class UserPrivacySettings extends Model
{
    protected $fillable     =   ['user_key','user_profile','diver_profile','dive_log',
        'my_reviews','contact_details','photos','friends','emergency_info'];

    public static function saveUserPrivacySettings($data)
    {
        $user_privacy_settings  =   new UserPrivacySettings();
        foreach ($data as $key=>$value){
            $user_privacy_settings->$key    =   $value;
        }

        $user_privacy_settings->save();
        return $user_privacy_settings;
    }
}
