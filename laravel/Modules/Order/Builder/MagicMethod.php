<?php

namespace Modules\Order\Builder;

trait MagicMethod
{
    public function __get($name)
    {
        if (method_exists($this, 'get'.ucfirst($name))) {
            return $this->{'get'.ucfirst($name)}();
        }

        return $this->{$name};
    }

    public function __isset($key)
    {
        return method_exists($this, 'get'.ucfirst($key));
    }
}
