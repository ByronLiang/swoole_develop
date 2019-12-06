<?php

namespace Modules\ReceivingPlace\Entities;

class ReceivingPlaceEvent
{
    /**
     * @param ReceivingPlace $model
     */
    public function creating($model)
    {
        if ($model->is_default) {
            $model->able
                ->receivingPlaces()
                ->whereIsDefault(true)
                ->update([
                    'is_default' => false,
                ]);
        } elseif (0 == $model->able->receivingPlaces()->count()) {
            $model->is_default = true;
        }
    }

    public function created($model)
    {
    }

    /**
     * @param ReceivingPlace $model
     */
    public function updating($model)
    {
        if ($model->is_default && $model->getOriginal('is_default') != $model->is_default) {
            $model->able
                ->receivingPlaces()
                ->whereIsDefault(true)
                ->update([
                    'is_default' => false,
                ]);
        }
    }

    public function updated($model)
    {
    }

    public function saving($model)
    {
    }

    public function saved($model)
    {
    }

    public function deleting($model)
    {
    }

    /**
     * @param ReceivingPlace $model
     */
    public function deleted($model)
    {
        if ($model->is_default) {
            $address = $model->able
                ->receivingPlaces()
                ->whereIsDefault(false)
                ->first();
            if ($address) {
                $address->is_default = true;
                $address->save();
            }
        }
    }

    public function restoring($model)
    {
    }

    public function restored($model)
    {
    }
}
