<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

use Illuminate\Support\Collection;

interface AdjustmentTraitInterface
{
    /**
     * @param AdjustmentInterface $adjustment
     *
     * @return mixed
     */
    public function addAdjustment(AdjustmentInterface $adjustment);

    /**
     * @return Collection
     */
    public function getAdjustments(): Collection;

    /**
     * @return float
     */
    public function getAdjustmentsTotal(): float;
}
