<?php

namespace App\Events;

use App\Scubaya\model\Merchant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Login
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $merchant;

    public $ipAddress;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($merchant, $ipAddress)
    {
        $this->ipAddress    =   $ipAddress;
        $this->merchant     =   $merchant;
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
}
