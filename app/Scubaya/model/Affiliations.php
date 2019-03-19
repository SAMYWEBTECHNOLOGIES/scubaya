<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Affiliations extends Model
{
    protected $table        =   'affiliations';

    public static function saveAffiliation($data)
    {
        $affiliation    =   new Affiliations();
        foreach($data as $key=>$value){
            $affiliation->$key  =   $value;
        }

        $affiliation->save();
        return $affiliation;
    }
}
