<?php

namespace App\Events;

use Hhxsv5\LaravelS\Swoole\Task\Event;

class TaskEvent extends Event
{
    public $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
