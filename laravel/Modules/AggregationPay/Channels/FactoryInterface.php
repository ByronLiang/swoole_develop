<?php

namespace Modules\AggregationPay\Channels;

interface FactoryInterface
{
    /**
     * @param string $notice_url
     *
     * @return mixed
     */
    public function setPayNoticeUrl(string $notice_url);

    /**
     * 支付回调.
     *
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function payNotice(\Closure $callback);

    /**
     * @param string $payment_no
     * @param float  $amount
     * @param string $subject
     * @param string $body
     * @param array  $extra      其他参数
     *
     * @return mixed
     */
    public function payCreate(string $payment_no, float $amount, string $subject, string $body, array $extra = []);

    /**
     * @param string $payment_no
     *
     * @return mixed
     */
    public function payQuery(string $payment_no);

    /**
     * @param string $payment_no
     *
     * @return mixed
     */
    public function isPaid(string $payment_no): bool;

    /**
     * @param string $payment_no
     * @param float  $payment_amount
     * @param string $refund_no
     * @param float  $refund_amount
     * @param string $refund_reason
     * @param array  $metadata       其他参数
     *
     * @return mixed
     */
    public function refundCreate(string $payment_no, float $payment_amount, string $refund_no, float $refund_amount, string $refund_reason, array $metadata = []);

    /**
     * @param string $payment_no
     * @param string $refund_no
     *
     * @return mixed
     */
    public function refundQuery(string $payment_no, string $refund_no);

    /**
     * @param string $payment_no
     * @param string $refund_no
     *
     * @return bool
     */
    public function isRefund(string $payment_no, string $refund_no): bool;
}
