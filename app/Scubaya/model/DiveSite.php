<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DiveSite extends Model
{

    use Notifiable;

    protected $table    =   'dive_site';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active', 'need_a_boat', 'name', 'max_depth', 'avg_depth', 'diver_level',
        'current', 'max_visibility', 'avg_visibility', 'type', 'image', 'country', 'latitude', 'longitude'
    ];

    public static function saveDiveSite($data)
    {
        $diveSite  =   new DiveSite();

        foreach($data as $key => $value){
            $diveSite->$key     =   $value;
        }

        $diveSite->save();

        return $diveSite;
    }

    public static function updateDiveSite($id, $data)
    {
        $diveSite  =   DiveSite::findOrFail($id);

        foreach($data as $key => $value){
            $diveSite->$key     =   $value;
        }

        $diveSite->update();

        return $diveSite;
    }
}
