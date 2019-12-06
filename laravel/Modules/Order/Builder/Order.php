<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

use Illuminate\Support\Collection;

/**
 * @property $itemsSubtotal float
 * @property $total float
 */
class Order implements OrderInterface
{
    use AdjustmentTrait, MagicMethod;

    /**
     * @var array|ItemInterface[]
     */
    protected $items = [];

    public function addItem(ItemInterface $item): OrderInterface
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems(): Collection
    {
        return collect($this->items);
    }

    public function getItemsSubtotal(): float
    {
        return $this->getItems()->pluck('subtotal')->sum();
    }

    public function getTotal(): float
    {
        return $this->getItemsSubtotal() + $this->getAdjustmentsTotal();
    }
}
