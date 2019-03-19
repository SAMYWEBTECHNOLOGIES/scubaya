<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserContactRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $merchantKey;
    public $email;
    public $query;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($merchantKey, $email, $query)
    {
        $this->merchantKey  =   $merchantKey[0];
        $this->email        =   $email;
        $this->query        =   $query;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        return new PrivateChannel('user-contact-request');
    }
}
