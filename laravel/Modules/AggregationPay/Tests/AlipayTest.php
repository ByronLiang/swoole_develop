<?php

namespace Modules\AggregationPay\Tests;

use Modules\AggregationPay\Sdk\Gatewaies\AlipayWap;
use Tests\TestCase;

class AlipayTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testWap()
    {
        $pay = AlipayWap::getInstance();

        dd($pay->chargeCreate(time(), 0.01, '345435', '32424'));
    }
}
