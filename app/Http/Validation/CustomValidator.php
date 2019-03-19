<?php
namespace App\Http\Validation;

use App\Scubaya\model\Rooms;

class CustomValidator {

    public function validateImageUploadCount($attribute, $value, $parameters, $validator)
    {
        if( count($value) > 20){
            return false;
        }
        return true;
    }

    public function validateRoomNumber($attribute, $value, $parameters, $validator)
    {
        if($parameters[1] != $parameters[2]){
            $query   =   Rooms::where('hotel_id', $parameters[0]);

            if($parameters[2]){
                $query->where('number', $parameters[2]);
            }else{
                $query->where('number', $parameters[1]);
            }

            return $query->exists() ? false : true ;
        }

        return true;
    }
}