<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WechatScanLogin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticker;
    public $user;
    public $api_token;

    public function __construct($ticker, $user, $api_token)
    {
        $this->ticker = $ticker;
        $this->user = $user;
        $this->api_token = $api_token;
    }

    public function broadcastOn()
    {
        return new Channel('scan-login');
    }
}
