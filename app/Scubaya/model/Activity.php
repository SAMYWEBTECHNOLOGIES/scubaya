<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Activity extends Model
{
    use Notifiable;

    protected $table    =   'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon', 'non_diving'];

    public static function saveActivity($data)
    {
        $activity  =   new Activity();

        foreach($data as $key => $value){
            $activity->$key     =   $value;
        }

        $activity->save();

        return $activity;
    }

    public static function updateActivity($id, $data)
    {
        $activity  =   Activity::findOrFail($id);

        foreach($data as $key => $value){
            $activity->$key     =   $value;
        }

        $activity->update();

        return $activity;
    }
}
