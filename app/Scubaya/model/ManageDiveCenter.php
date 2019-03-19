<?php

namespace App\Scubaya\model;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;


class ManageDiveCenter extends Model
{
    use Searchable;

    protected $table        =   "manage_dive_centers";
    protected $fillable     =   ['merchant_key','name','image','gallery','address','city','state','country','zipcode','latitude','longitude'];

    public static function saveDiveCenter($data)
    {
        $dive_center     =   new ManageDiveCenter();

        foreach ($data as   $key=>$value){
            $dive_center->$key   =   $value;
        }
        $dive_center->save();

        return $dive_center;
    }

    public static function updateDiveCenter($id, $data)
    {
        $dive_center   =   ManageDiveCenter::find($id);

        foreach($data as $key => $value){
            $dive_center->$key =   $value;
        }
        $dive_center->update();

        return $dive_center;
    }
}
