<?php

namespace Modules\Order\Tests;

use Modules\Order\Builder\Adjustment;
use Modules\Order\Builder\Item;
use Modules\Order\Builder\ItemAbleInterface;
use Modules\Order\Builder\Order;
use Tests\TestCase;

class OrderCalculation extends TestCase
{
    /**
     * 单个产品金额计算.
     *
     * @return Item
     *
     * @throws \Exception
     */
    public function testItem()
    {
        $item = new Item($this->getProduct(), 1.1, 2);

        $item->addAdjustment(new Adjustment(-0.1, 'coupon', 'offer'));
        $item->addAdjustment(new Adjustment(0.4, 'shipping', 'cost'));

        $this->assertTrue(2.2 == $item->unitTotal);
        $this->assertTrue(2.5 == $item->subtotal);
    }

    /**
     * 订单金额计算.
     *
     * @throws \Exception
     */
    public function testOrder()
    {
        $order = new Order();

        $order->addItem((new Item($this->getProduct(), 1.1, 2))->addAdjustment(new Adjustment(-0.1, 'promotion', 'offer')));
        $order->addItem(new Item($this->getProduct(), 2.5, 7));

        $order->addAdjustment(new Adjustment(-0.1, 'coupon', 'offer'));
        $order->addAdjustment(new Adjustment(0.4, 'shipping', 'cost'));

        $this->assertTrue(19.6 == $order->itemsSubtotal);
        $this->assertTrue(19.9 == $order->total);
    }

    private function getProduct()
    {
        return new class() implements ItemAbleInterface {
            public $id = 1;
        };
    }
}
