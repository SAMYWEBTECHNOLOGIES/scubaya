<?php

namespace App\Listeners;

use App\Events\VerifyRole;
use App\Scubaya\model\Group;
use App\Scubaya\model\User;
use App\Scubaya\model\Notification;

class LogVerifiedRole
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
     * This will log user role verification notification in database
     * @param  VerifyRole  $event
     * @return void
     */
    public function handle(VerifyRole $event)
    {
        $storedNotification     =   $this->logUserNotification($event);

        $this->notificationId   =   $storedNotification->id;
    }

    public function logUserNotification($event)
    {
        $merchantDetails    =   User::where('id', $event->merchantId)->first();

        $notification       =   new Notification();

        $notification->user_id              =   $event->userId;
        //$notification->user_type            =   MERCHANT_USER_ROLE;
        //$notification->verification_type    =   'role';
        $notification->status               =   0;
        $notification->params               =   json_encode([
            'merchant_id'    =>  $event->merchantId,
            'role_ids'       =>  $this->getRoleIds($event->roleIds)
        ]);
        $notification->message              =   trans("notification.role_verification", [
            'roles'     =>  implode(', ', $this->getRoleNames($event->roleIds)),
            'merchant'  =>  ucwords($merchantDetails->first_name).' '.ucwords($merchantDetails->last_name),
            'url'       =>  route('scubaya::user::settings::account_settings', [$event->userId])
        ]);

        return Notification::saveNotification($notification);
    }

    /**
     * To get role ids
     * @param $roles
     * @return string
     */
    public function getRoleIds($roles)
    {
        $Roles  =   array();

        $roles  =   (array)json_decode($roles);

        foreach($roles as $key => $value) {
            $Roles[]    =   $key;

            $value          =   (object)$value;

            if(isset($value->extra_role)) {
                foreach ($value->extra_role as $role) {
                    if($role == DIVE_MASTER) {
                        $Roles[]    =   DIVE_MASTER;
                    }

                    if ($role == DIVE_GUIDE) {
                        $Roles[]    =   DIVE_GUIDE;
                    }
                }
            }
        }

        return json_encode($Roles);
    }

    /**
     * To get role names
     * @param $roles
     * @return array
     */
    public function getRoleNames($roles)
    {
        $Roles      =   array();

        $roles          =   (array)json_decode($roles);

        foreach($roles as $key => $value) {
            $Roles[]    =   Group::where('id', $key)->value('name');

            $value          =   (object)$value;

            if(isset($value->extra_role)) {
                foreach ($value->extra_role as $role) {
                    if($role == DIVE_MASTER) {
                        $Roles[]    =   'Dive Master';
                    }

                    if ($role == DIVE_GUIDE) {
                        $Roles[]    =   'Dive Guide';
                    }
                }
            }
        }

        return $Roles;
    }
}
