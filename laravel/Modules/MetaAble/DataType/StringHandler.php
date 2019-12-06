<?php

namespace Modules\MetaAble\DataType;

/**
 * Handle serialization of strings.
 *
 * @author Sean Fraser <sean@plankdesign.com>
 */
class StringHandler extends ScalarHandler
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'string';
}
