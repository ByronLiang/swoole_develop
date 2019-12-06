<?php

namespace Modules\MetaAble\Entities;

use Modules\MetaAble\DataType\Registry;

class MetaAble extends \App\Models\Model
{
    public $timestamps = true;

    protected $guarded = ['id', 'able_type', 'able_id', 'type'];

    protected $attributes = [
        'type' => 'null',
        'value' => '',
    ];

    protected $cachedValue;

    public function able()
    {
        return $this->morphTo();
    }

    public function getRawValue()
    {
        return $this->attributes['value'];
    }

    /**
     * Accessor for value.
     *
     * Will unserialize the value before returning it.
     *
     * Successive access will be loaded from cache.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if (!isset($this->cachedValue)) {
            $this->cachedValue = $this->getDataTypeRegistry()
                ->getHandlerForType($this->type)
                ->unserializeValue($this->attributes['value']);
        }

        return $this->cachedValue;
    }

    /**
     * Mutator for value.
     *
     * The `type` attribute will be automatically updated to match the datatype of the input.
     *
     * @param mixed $value
     */
    public function setValueAttribute($value)
    {
        $registry = $this->getDataTypeRegistry();

        $this->attributes['type'] = $registry->getTypeForValue($value);
        $this->attributes['value'] = $registry->getHandlerForType($this->type)
            ->serializeValue($value);

        $this->cachedValue = null;
    }

    /**
     * Load the datatype Registry from the container.
     *
     * @return Registry
     */
    protected function getDataTypeRegistry(): Registry
    {
        $registry = new Registry();
        foreach (config('meta_able.datatypes') as $handler) {
            $registry->addHandler(new $handler());
        }

        return $registry;
    }

    public function toArray()
    {
        $data = parent::toArray();

        return $data['value'];
    }
}
