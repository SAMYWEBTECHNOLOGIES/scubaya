<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class DynamicContent extends Model
{
    protected $fillable     =   ['active','name','slug','content'];

    public static function saveDynamicContent($data)
    {
        $dynamic_content    =   new DynamicContent();
        foreach ($data as $key=>$value){
            $dynamic_content->$key  =   $value;
        }
        $dynamic_content->save();
    }
}
