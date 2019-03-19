<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VerifyRole
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $merchantId;
    public $roleIds;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $merchantId, $roleIds)
    {
        $this->userId       =   $userId;
        $this->merchantId   =   $merchantId;
        $this->roleIds      =   $roleIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('verify-user-role-channel'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'verifyRole';
    }
}
