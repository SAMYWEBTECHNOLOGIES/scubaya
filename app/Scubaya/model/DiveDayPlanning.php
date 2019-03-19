<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class DiveDayPlanning extends Model
{
    public static function saveDiveDayPlanning($data)
    {
        $dive_day_planning      =   new DiveDayPlanning();

        foreach ($data as $key=>$value)
        {
            $dive_day_planning->$key    =   $value;
        }

        $dive_day_planning->save();

        return $dive_day_planning;
    }
}
