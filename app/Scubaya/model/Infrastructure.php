<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Infrastructure extends Model
{
    use Notifiable;

    protected $table    =   'infrastructure';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon'];

    public static function saveInfrastructure($data)
    {
        $infrastructure  =   new Infrastructure();

        foreach($data as $key => $value){
            $infrastructure->$key     =   $value;
        }

        $infrastructure->save();

        return $infrastructure;
    }

    public static function updateInfrastructure($id, $data)
    {
        $infrastructure  =   Infrastructure::findOrFail($id);

        foreach($data as $key => $value){
            $infrastructure->$key     =   $value;
        }

        $infrastructure->update();

        return $infrastructure;
    }
}
