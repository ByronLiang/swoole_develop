<?php

namespace Modules\AggregationPay;

use Modules\AggregationPay\Entities\ApPaymentRecord;
use Modules\AggregationPay\Entities\ApRefundRecord;

/**
 * @mixin \Eloquent
 */
trait AggregationPayTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ApPaymentRecord
     */
    public function paymentRecords()
    {
        return $this->morphMany(ApPaymentRecord::class, 'able');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApPaymentRecord
     */
    public function paymentRecord()
    {
        return $this->morphOne(ApPaymentRecord::class, 'able');
    }

    /**
     * 获取已经支付的支付记录.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApPaymentRecord
     */
    public function paymentRecordPaid()
    {
        return $this->paymentRecord()->where('status', ApPaymentRecord::STATUS_PAID);
    }

    /**
     * 获取已经支付的支付记录.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApPaymentRecord
     */
    public function paymentRecordPaidRefunded()
    {
        return $this->paymentRecord()->whereIn('status', [ApPaymentRecord::STATUS_PAID, ApPaymentRecord::STATUS_REFUNDED]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ApRefundRecord
     */
    public function refundRecords()
    {
        return $this->morphMany(ApRefundRecord::class, 'able');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ApRefundRecord
     */
    public function refundRecordsRefunded()
    {
        return $this->refundRecords()->where('status', ApRefundRecord::STATUS_REFUNDED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApRefundRecord
     */
    public function refundRecord()
    {
        return $this->morphOne(ApRefundRecord::class, 'able');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApRefundRecord
     */
    public function refundRecordRefunded()
    {
        return $this->refundRecord()->where('status', ApRefundRecord::STATUS_REFUNDED);
    }
}
