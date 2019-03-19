<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table    = "configuration";

    public $timestamps  = false;

    public static function getConfig($key)
    {
        $record = self::query()
            ->where('key', $key)
            ->first();

        if($record){
            return $record->value;
        } else{
            return null;
        }
    }

    public static function setConfig($key, $value)
    {
        return self::query()
            ->where('key', $key)
            ->update([
                'value' => $value
            ]);
    }
}
