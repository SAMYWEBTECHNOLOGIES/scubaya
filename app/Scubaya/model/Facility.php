<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Facility extends Model
{
    use Notifiable;

    protected $table    =   'dive_center_facilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon'];

    public static function saveFacility($data)
    {
        $facility  =   new Facility();

        foreach($data as $key => $value){
            $facility->$key     =   $value;
        }

        $facility->save();

        return $facility;
    }

    public static function updateFacility($id, $data)
    {
        $facility  =   Facility::findOrFail($id);

        foreach($data as $key => $value){
            $facility->$key     =   $value;
        }

        $facility->update();

        return $facility;
    }
}
