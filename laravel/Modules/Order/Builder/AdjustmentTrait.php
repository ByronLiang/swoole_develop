<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

use Illuminate\Support\Collection;

/**
 * @property $adjustmentsTotal float
 * @property $adjustments Collection
 */
trait AdjustmentTrait
{
    /**
     * @var array|AdjustmentInterface[]
     */
    protected $adjustments = [];

    /**
     * @param AdjustmentInterface $adjustment
     *
     * @return self
     */
    public function addAdjustment(AdjustmentInterface $adjustment)
    {
        $this->adjustments[] = $adjustment;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAdjustments(): Collection
    {
        return collect($this->adjustments);
    }

    public function getAdjustmentsTotal(): float
    {
        return $this->getAdjustments()->pluck('amount')->sum();
    }
}
