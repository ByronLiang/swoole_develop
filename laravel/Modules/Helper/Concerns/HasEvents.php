<?php

namespace Modules\Helper\Concerns;

trait HasEvents
{
    protected $listens = [];

    public function listen($event, $callback, $once = false)
    {
        if (!is_callable($callback)) {
            return false;
        }
        $listen = [
            'callback' => $callback,
            'once' => $once,
        ];
        if (!isset($this->listens[$event]) || !in_array($listen, $this->listens[$event])) {
            $this->listens[$event][] = $listen;
        }

        return $this;
    }

    public function one($event, $callback)
    {
        return $this->listen($event, $callback, true);
    }

    public function remove($event, $index = null)
    {
        if (is_null($index)) {
            unset($this->listens[$event]);
        } else {
            unset($this->listens[$event][$index]);
        }
    }

    public function fireModelEvent()
    {
        if (!func_num_args()) {
            return;
        }
        $args = func_get_args();
        $event = array_shift($args);

        if (!isset($this->listens[$event])) {
            return false;
        }

        foreach ((array) $this->listens[$event] as $index => $listen) {
            $callback = $listen['callback'];
            $listen['once'] && $this->remove($event, $index);
            call_user_func_array($callback, $args);
        }
    }
}
