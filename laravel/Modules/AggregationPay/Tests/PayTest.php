<?php

namespace Modules\AggregationPay\Tests;

use Modules\AggregationPay\AggregationPay;
use Modules\Order\Entities\Order;
use Tests\TestCase;

class PayTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testExample()
    {
        $pay = new AggregationPay();

        $order = Order::create([
            'user_id' => 1,
            'amount' => 4,
            'status' => 'wait_send',
            'paid_at' => \Carbon\Carbon::now(),
        ]);

        $res = $pay->payment('alipay_app', $order, 1, '测试支付');
//        $res = $pay->payment('alipay_web', $order, 1, '测试支付');
//        $res = $pay->payment('wechat_app', $order, 1, '测试支付');
//        $res = $pay->paymentQuery('201811051812247');
        dd($res);
    }
}
