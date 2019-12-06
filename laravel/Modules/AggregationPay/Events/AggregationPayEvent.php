<?php

namespace Modules\AggregationPay\Events;

use Modules\AggregationPay\Entities\ApPaymentRecord;
use Modules\AggregationPay\Entities\ApRefundRecord;
use Illuminate\Queue\SerializesModels;

class AggregationPayEvent
{
    use SerializesModels;

    /**
     * @var ApPaymentRecord|ApRefundRecord
     */
    public $record;

    /**
     * PaymentSuccess constructor.
     *
     * @param ApPaymentRecord|ApRefundRecord $record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * @return bool
     */
    public function isPayment()
    {
        return $this->record instanceof ApPaymentRecord;
    }

    /**
     * @return bool
     */
    public function isRefund()
    {
        return $this->record instanceof ApRefundRecord;
    }
}
