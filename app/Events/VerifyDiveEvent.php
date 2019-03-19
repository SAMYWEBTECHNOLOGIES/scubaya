<?php

namespace App\Events;

use App\Scubaya\model\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class VerifyDiveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $SCBY_UID;
    public $authId;
    public $diveLogId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($scby_uid, $auth_id, $userdiveLog_id)
    {
        $this->SCBY_UID     =   $scby_uid;
        $this->authId       =   $auth_id;
        $this->diveLogId    =   $userdiveLog_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('private-App.User.'.$this->SCBY_UID);
    }

    public function broadcastAs()
    {
        return 'verifyDive';
    }
}
