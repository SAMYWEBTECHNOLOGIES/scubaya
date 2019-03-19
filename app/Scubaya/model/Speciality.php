<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Speciality extends Model
{
    use Notifiable;

    protected $table    =   'specialities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon'];

    public static function saveSpeciality($data)
    {
        $speciality  =   new Speciality();

        foreach($data as $key => $value){
            $speciality->$key     =   $value;
        }

        $speciality->save();

        return $speciality;
    }

    public static function updateSpeciality($id, $data)
    {
        $speciality  =   Speciality::findOrFail($id);

        foreach($data as $key => $value){
            $speciality->$key     =   $value;
        }

        $speciality->update();

        return $speciality;
    }
}
