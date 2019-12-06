<?php

namespace Modules\Wechat\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class ScanEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $open_id;
    public $user_info;
    public $ticker;
    public $type;

    public function __construct($open_id, $user_info, $ticker, $type)
    {
        $this->open_id = $open_id;
        $this->user_info = $user_info;
        $this->ticker = $ticker;
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
