<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

/**
 * @property $unitTotal float
 * @property $subtotal float
 */
class Item implements ItemInterface
{
    use AdjustmentTrait, MagicMethod;

    public $able;

    protected $unitPrice = 0;

    protected $quantity = 0;

    protected $adjustments = [];

    /**
     * Item constructor.
     *
     * @param ItemAbleInterface $able
     * @param float             $unitPrice
     * @param int               $quantity
     *
     * @throws \Exception
     */
    public function __construct(ItemAbleInterface $able, float $unitPrice, int $quantity)
    {
        $this->able = $able;
        $this
            ->setUnitPrice($unitPrice)
            ->setQuantity($quantity);
    }

    /**
     * @param float $unitPrice
     *
     * @return ItemInterface
     *
     * @throws \Exception
     */
    public function setUnitPrice(float $unitPrice): ItemInterface
    {
        if ($unitPrice <= 0) {
            throw new \Exception('无效单价');
        } else {
            $this->unitPrice = $unitPrice;
        }

        return $this;
    }

    /**
     * @param float $quantity
     *
     * @return ItemInterface
     *
     * @throws \Exception
     */
    public function setQuantity(float $quantity): ItemInterface
    {
        if ($quantity <= 0) {
            throw new \Exception('无效数量');
        } else {
            $this->quantity = $quantity;
        }

        return $this;
    }

    public function getUnitTotal(): float
    {
        return (float) ($this->unitPrice * $this->quantity);
    }

    public function getSubtotal(): float
    {
        return $this->getUnitTotal() + $this->getAdjustmentsTotal();
    }
}
