<?php

namespace Modules\MetaAble\DataType;

/**
 * Handle serialization of plain objects.
 *
 * @author Sean Fraser <sean@plankdesign.com>
 */
class ObjectHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'object';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return is_object($value);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        return json_decode($value, false);
    }
}
