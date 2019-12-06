<?php

namespace Modules\Order\Builder;

interface MagicMethodInterface
{
    public function __isset($key);

    public function __get($name);
}
