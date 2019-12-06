<?php

namespace Modules\MetaAble\DataType;

/**
 * Handle serialization of integers.
 *
 * @author Sean Fraser <sean@plankdesign.com>
 */
class IntegerHandler extends ScalarHandler
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'integer';
}
