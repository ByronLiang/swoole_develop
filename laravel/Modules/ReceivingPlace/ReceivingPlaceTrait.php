<?php

namespace Modules\ReceivingPlace;

use Modules\ReceivingPlace\Entities\ReceivingPlace;

/**
 * @mixin \Eloquent
 */
trait ReceivingPlaceTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ReceivingPlace
     */
    public function receivingPlaces()
    {
        return $this->morphMany(ReceivingPlace::class, 'able');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ReceivingPlace
     */
    public function receivingPlace()
    {
        return $this->morphOne(ReceivingPlace::class, 'able');
    }

    public function defaultReceivingPlace()
    {
        return $this->receivingPlace()->where('is_default', true);
    }
}
