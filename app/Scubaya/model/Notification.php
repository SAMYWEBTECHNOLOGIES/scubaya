<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use Notifiable;

    protected $table    =   'notifications';

    public static function saveNotification($data)
    {
        $notification   =   new Notification();

        foreach($data as $key => $value){
            $notification->$key =   $value;
        }
        $notification->save();

        return $notification;
    }

    public static function updateNotificationStatusToRead($id)
    {
        $notification         =   Notification::find($id);

        $notification->status = 1;

        $notification->update();

        return $notification;
    }
}
