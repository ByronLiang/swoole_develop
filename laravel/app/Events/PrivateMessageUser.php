<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrivateMessageUser implements ShouldBroadcast
{
    public $message;
    public $user_id;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct($message, $user_id)
    {
        $this->message = $message;
        $this->user_id = $user_id;
    }

    public function broadcastOn()
    {
        return new Channel('private_message_user_'.$this->user_id);
    }
}
