<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class UserLogDive extends Model
{

    //
    protected $table    =   "user_log_dives";

    public static function saveUserDiveLog($data)
    {
        $user_log_dive     =   new UserLogDive();

        foreach ($data as   $key=>$value){
            $user_log_dive->$key   =   $value;
        }
        $user_log_dive->save();

        return $user_log_dive;
    }

    public static function updateUserDiveLog($id, $data)
    {
        $user_log_dive     =   UserLogDive::find($id);

        foreach ($data as   $key => $value){
            $user_log_dive->$key   =   $value;
        }
        $user_log_dive->save();

        return $user_log_dive;
    }
}
