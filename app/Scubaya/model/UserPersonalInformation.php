<?php

namespace App\Scubaya\model;

use App\Encryption\Encryption;
use Illuminate\Database\Eloquent\Model;

class UserPersonalInformation extends Model
{
    use Encryption;
    /**
    *   The values that have to be encrypt
    *   @var array
    */
    private $encryptable    =   [
        'gender','dob','user_name','first_name','last_name','nationality',
        'email','phone','mobile','street','house_number','house_number_extension','postal_code','city',
        'country','image'
    ];

    protected $fillable     =   ['user_key','gender','dob','user_name','first_name','last_name','nationality',
        'email','phone','mobile','street','house_number','house_number_extension','postal_code','city',
        'country','image'
    ];

    public static function formatDataToShow($data,$name)
    {
        $dataToShow  =   [];

        if($data) {
            foreach (json_decode($data) as $key => $value) {
                if ($key == '_empty_') {
                    $dataToShow  =  (object) [ $name  =>  null, 'show' =>  $value ];
                } else {
                    $dataToShow  =  (object) [ $name  =>  $key, 'show' =>  $value ];
                }
            }
        }

        return $dataToShow;
    }
}