<?php

namespace App\Events;

use App\PasswordReset;
use Illuminate\Broadcasting\PrivateChannel;

class PasswordResetUpdatedCreatedEvent
{
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $password)
    {
        $this->password = $password;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
