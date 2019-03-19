<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WebsiteDetails extends Model
{
    use Notifiable;

    protected $table    =   'website_details';

    public static function saveDetails($data)
    {
        $detail   =   new WebsiteDetails();

        foreach($data as $key => $value){
            $detail->$key =   $value;
        }
        $detail->save();

        return $detail;
    }

    public static function updateDetails($id, $data)
    {
        $detail   =   WebsiteDetails::find($id);

        foreach($data as $key => $value){
            $detail->$key =   $value;
        }

        $detail->update();

        return $detail;
    }
}
