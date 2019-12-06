<?php

namespace Modules\AggregationPay\Channels;

/**
 * @method setPayNoticeUrl($notice_url)
 * @method payNotice(\Closure $callback)
 * @method payCreate(string $payment_no, float $amount, string $subject, string $body, array $extra = [])
 * @method payQuery(string $payment_no)
 * @method isPaid(string $payment_no): bool
 * @method refundCreate(string $payment_no, float $payment_amount, string $refund_no, float $refund_amount, string $description, array $metadata = [])
 * @method refundQuery(string $payment_no, string $refund_no)
 * @method isRefund(string $payment_no, string $refund_no): bool
 * @mixin FactoryInterface
 */
class Factory
{
    /**
     * @var FactoryInterface
     */
    private $channel;

    /**
     * SdkFactory constructor.
     *
     * @param $channel
     *
     * @throws \Exception
     */
    public function __construct(string $channel)
    {
        switch ($channel) {
            case 'wechat_public':
                $this->channel = WechatPay::getInstance(str_replace('wechat_', '', $channel))
                    ->setChannel($this->channel);
                break;
            case 'alipay_app':
                $this->channel = AlipayPay::getInstance(str_replace('alipay_', '', $channel))
                    ->setChannel($this->channel);
                break;
            case 'alipay_web':
                $this->channel = AlipayPay::getInstance(str_replace('alipay_', '', $channel))
                    ->setChannel($this->channel);
                break;
            default:
                abort(500, '未定义支付方式');
                break;
        }
        if (!$this->channel instanceof FactoryInterface) {
            abort(500, '未 implements FactoryInterface');
        }
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->channel, $name], $arguments);
    }
}
