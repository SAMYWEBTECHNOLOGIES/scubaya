<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class DiveCenter extends Model
{
    protected $table            =   'dive_centers';
    //protected $connection       =   'merchant';

    //    protected $fillable         =   [''];

    public static function saveDiveCenter($data)
    {
        $diveCenter  =   new DiveCenter();
        foreach($data as $key=>$value)
        {
            $diveCenter->$key  =   $value;
        }
        $diveCenter->save();
        return new static;
//        return $diveCenter;
    }
}
