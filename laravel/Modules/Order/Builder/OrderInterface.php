<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

use Illuminate\Support\Collection;

interface OrderInterface extends AdjustmentTraitInterface, MagicMethodInterface
{
    public function addItem(ItemInterface $item): self;

    public function getItems(): Collection;

    public function getItemsSubtotal(): float;

    public function getTotal(): float;
}
