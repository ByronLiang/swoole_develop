<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

interface ItemInterface extends AdjustmentTraitInterface, MagicMethodInterface
{
    public function setUnitPrice(float $unitPrice): self;

    public function setQuantity(float $quantity): self;

    public function getUnitTotal(): float;

    public function getSubtotal(): float;
}
