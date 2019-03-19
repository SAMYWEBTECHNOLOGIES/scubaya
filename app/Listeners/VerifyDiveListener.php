<?php

namespace App\Listeners;

use App\Events\VerifyDiveEvent;
use App\Scubaya\model\User;
use App\Scubaya\model\Notification;

class VerifyDiveListener
{
    public $message;
    public $count_of_notification;
    public $notification_id;
    public $log_dive_id;
    public $SCBY_UID;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VerifyDiveEvent  $event
     * @return void
     */
    public function handle(VerifyDiveEvent $event)
    {
        $first_name         =   User::where('id',$event->authId)->value('first_name');
        $message            =   trans('notification.dive_log_verification', [
            'name'      =>  decrypt($first_name),
            'log_id'    =>  $event->diveLogId
        ]);
        $receiving_user_id  =   User::where('UID',$event->SCBY_UID)->value('id');

        $notification                       =   new \stdClass();
        $notification->user_id              =   $receiving_user_id;
        //$notification->user_type            =   \USER;
        //$notification->verification_type    =   'dive_log';
        $notification->status               =   0;
        $notification->params               =   json_encode([
            'dive_log_id'   =>  $event->diveLogId
        ]);
        $notification->message              =   $message;
        $loggedNotification                 =   Notification::saveNotification($notification);

        $notifications                      =   json_decode(Notification::where('user_id',$receiving_user_id)->get());

        $this->notification_id              =   $loggedNotification->id;
        $this->count_of_notification        =   count($notifications);
        $this->SCBY_UID                     =   $event->SCBY_UID;
        $this->message                      =   $message;
        $this->log_dive_id                  =   $event->diveLogId;
    }
}
