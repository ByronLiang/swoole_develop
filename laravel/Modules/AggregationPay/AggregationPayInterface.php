<?php

namespace Modules\AggregationPay;

use Modules\AggregationPay\Entities\ApPaymentRecord;
use Modules\AggregationPay\Entities\ApRefundRecord;
use Illuminate\Support\Collection;

/**
 * @property Collection      $paymentRecords
 * @property ApPaymentRecord $paymentRecord
 * @property ApPaymentRecord $paymentRecordPaid
 * @property Collection      $refundRecords
 * @property ApRefundRecord  $refundRecord
 */
interface AggregationPayInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ApPaymentRecord
     */
    public function paymentRecords();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApPaymentRecord
     */
    public function paymentRecord();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApPaymentRecord
     */
    public function paymentRecordPaid();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|ApRefundRecord
     */
    public function refundRecords();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|ApRefundRecord
     */
    public function refundRecord();
}
