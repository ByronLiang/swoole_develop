<?php

namespace App\Listeners;

use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class TaskEventListener extends Listener
{
    public function __construct()
    {
    }

    public function handle(Event $event)
    {
        \Log::info(__CLASS__.':handle start', [$event->getData()]);
    }
}
