<?php

namespace Modules\AggregationPay\Channels;

use Modules\AggregationPay\Services\WechatService;

/**
 * TODO: 缺失退款回调通知.
 */
class WechatPay extends WechatService implements FactoryInterface
{
    private $channel;

    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @param string $notice_url
     *
     * @return mixed
     */
    public function setPayNoticeUrl(string $notice_url)
    {
        return $this->setNotifyUrl($notice_url);
    }

    /**
     * @param \Closure $callback
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function payNotice(\Closure $callback)
    {
        return $this->payment()->handlePaidNotify(function ($message, $fail) use ($callback) {
            return $callback($message['out_trade_no'], $message['transaction_id']);
        });
    }

    /**
     * @param string $payment_no
     * @param float  $amount
     * @param string $subject
     * @param string $body
     * @param array  $extra
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function payCreate(string $payment_no, float $amount, string $subject, string $body, array $extra = [])
    {
        $trade_type = 'JSAPI';

        return $this->paymentConfig(
            $payment_no,
            $amount,
            $subject,
            $body,
            $trade_type,
            $extra['open_id'] ?? ''
        );
    }

    /**
     * @param string $payment_no
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function payQuery(string $payment_no)
    {
        return $this->payment()
            ->order
            ->queryByOutTradeNumber($payment_no);
    }

    /**
     * @param string $payment_no
     *
     * @return bool
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function isPaid(string $payment_no): bool
    {
        $res = $this->payQuery($payment_no);

        if ('SUCCESS' == $res['return_code'] && 'SUCCESS' == $res['trade_state']) {
            return true;
        }

        \Log::debug(__METHOD__, $res);

        return false;
    }

    /**
     * @param string $payment_no
     * @param float  $payment_amount
     * @param string $refund_no
     * @param float  $refund_amount
     * @param string $refund_reason
     * @param array  $metadata
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function refundCreate(string $payment_no, float $payment_amount, string $refund_no, float $refund_amount, string $refund_reason, array $metadata = [])
    {
        $res = $this->refund(
            $payment_no,
            $refund_no,
            $payment_amount,
            $refund_amount
        );

        if ('SUCCESS' == $res['return_code'] && 'SUCCESS' == $res['result_code']) {
            return $res;
        }

        // if(in_array($result->err_code, ['ERROR', 'USER_ACCOUNT_ABNORMAL'])){
        //无法退款，取消操作
        // }
        if ('NOTENOUGH' == $res['err_code']) {
            abort(403, '微信商户平台可用余额不足，请充值后重试');
        }

        \Log::error(__METHOD__, $res);
        abort(500, json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param string $payment_no
     * @param string $refund_no
     *
     * @return array
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function refundQuery(string $payment_no, string $refund_no)
    {
        return $this->payment()
            ->refund
            ->queryByOutRefundNumber($refund_no);
    }

    /**
     * @param string $payment_no
     * @param string $refund_no
     *
     * @return bool
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function isRefund(string $payment_no, string $refund_no): bool
    {
        $res = $this->refundQuery($payment_no, $refund_no);

        return (bool) $res;
    }
}
