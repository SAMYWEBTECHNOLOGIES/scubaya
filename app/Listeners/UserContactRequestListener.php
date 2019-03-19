<?php

namespace App\Listeners;

use App\Events\UserContactRequest;
use App\Scubaya\model\ContactRequest;
use App\Scubaya\model\User;
use App\Scubaya\model\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;

class UserContactRequestListener
{
    public $notificationId;

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
     * @param  UserContactRequest  $event
     * @return void
     */
    public function handle(UserContactRequest $event)
    {
        $notification       =   $this->logContactRequestNotification($event);
        $this->logContactRequest($event);

        $this->notificationId   =   $notification->id;
    }

    public function logContactRequestNotification($event)
    {
        $all_user_emails    =   User::where('is_user',IS)->get();

        $userDetails        =   $all_user_emails->first(function ($value, $key) use ($event) {
            if(Crypt::decrypt($value->email)    ==  $event->email) {
                return $value;
            }
        });

        $notification       =   new Notification();

        $notification->user_id              =   $event->merchantKey;
        $notification->params               =   json_encode([
            'email'    =>   $event->email,
            'query'    =>   $event->query,
        ]);
        $notification->message              =   trans("notification.user_contact_query", [
            'diver'     =>  User::decryptString($userDetails->first_name).' '.User::decryptString($userDetails->last_name)
        ]);

        return Notification::saveNotification($notification);
    }

    private function logContactRequest($event)
    {
        $request    =   new \stdClass();

        $request->merchant_key  =   $event->merchantKey;
        $request->query         =   $event->query;

        ContactRequest::saveContactRequest($request);
    }
}
