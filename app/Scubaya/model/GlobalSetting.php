<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    /* saving the settings that are set by admin and used globally as default */
    public static function saveGlobalSettings($all,$prefix_1,$prefix_2)
    {
        foreach($all as $name=>$value){
            $name                       =   $prefix_1.'.'.$prefix_2.'.'.$name;
            $check  =   GlobalSetting::where('name',$name);
            if($check->exists()){
                if(!($check->value('value')  ==  $value)){
                    $check->update(['value'=>$value]);
                }
            }else{
                $global_settings            =   new GlobalSetting();
                $global_settings->name      =   $name;
                $global_settings->value     =   $value;
                $global_settings->save();
            }
        }
    }

    public static function saveApiSettings($data)
    {
        foreach($data as $name=>$value)
        {
            $check  =   GlobalSetting::where('name',$name);
            if($check->exists()){
                if(!($check->value('value')  ==  $value)){
                    $check->update(['value'=>$value]);
                }
            }else{
                $apiSettings            =   new GlobalSetting();
                $apiSettings->name      =   $name;
                $apiSettings->value     =   $value;
                $apiSettings->save();
            }
        }
    }
}
