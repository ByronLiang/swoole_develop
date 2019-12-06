<?php

declare(strict_types=1);

namespace Modules\Order\Builder;

class Adjustment implements AdjustmentInterface
{
    public $amount = 0;

    public $type;

    public $label;

    public function __construct(float $amount, string $label, string $type = null)
    {
        $this->amount = $amount;

        $this->label = $label;

        $this->type = $type;
    }
}
