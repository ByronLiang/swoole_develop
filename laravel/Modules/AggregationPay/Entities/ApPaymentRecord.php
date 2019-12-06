<?php

namespace Modules\AggregationPay\Entities;

use Modules\AggregationPay\Events\AggregationPayEvent;
use Modules\AggregationPay\Channels\Factory;

/**
 * @method static |self whereStatus($status)
 *
 * @property int    $id
 * @property float  $amount
 * @property float  $refund_amount
 * @property string $status
 */
class ApPaymentRecord extends \App\Models\Model
{
    const STATUS_PAYING = 'paying';
    const STATUS_PAID = 'paid';
    const STATUS_REFUNDED = 'refunded';

    protected $hidden = [
         'able_id',
         'able_type',
    ];

    protected $attributes = [
        'status' => self::STATUS_PAYING,
    ];

    protected $casts = [
        'amount' => 'float',
        'refund_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::updating(function (self $self) {
            if ($self->refund_amount != $self->getOriginal('refund_amount') && $self->refund_amount == $self->amount) {
                $self->statusToRefunded();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|self
     */
    public function able()
    {
        return $this->morphTo();
    }

    /**
     * @param $notify_url
     * @param array $extra
     *
     * @return |null
     *
     * @throws \Exception
     */
    public function initiatePayment($notify_url, $extra = [])
    {
        if (self::STATUS_PAYING != $this->status) {
            throw new \Exception('已支付');
        }

        if (!$this->amount || $this->amount < 0.01) {
            $this->statusToPaid();

            return null;
        }

        if ('test' == $this->channel) {
            $this->statusToPaid();

            return null;
        }

        if (!$this->payment_no) {
            $this->payment_no = date('YmdHis').$this->id;
            $this->save();
        }

        if (!isset($extra['return_url'])) {
            $extra['return_url'] = url('');
        }
        if (!strpos($extra['return_url'], '://')) {
            $extra['return_url'] = url($extra['return_url']);
        }

        $factory = new Factory($this->channel);
        $factory->setPayNoticeUrl($notify_url);

        return $factory->payCreate(
            $this->payment_no,
            $this->amount,
            $this->remark,
            '',
            $extra
        );
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function checkStatus()
    {
        if (self::STATUS_PAYING != $this->status) {
            return true;
        }

        $factory = new Factory($this->channel);
        $res = $factory->isPaid($this->payment_no);

        if (true === $res) {
            $this->statusToPaid();
        }

        return $res;
    }

    public function statusToPaid()
    {
        if (self::STATUS_PAID == $this->status) {
            return $this;
        }
        $this->status = self::STATUS_PAID;
        $this->save();

        event(new AggregationPayEvent($this));

        return $this;
    }

    public function statusToRefunded()
    {
        if (self::STATUS_REFUNDED == $this->status) {
            return $this;
        }
        $this->status = self::STATUS_REFUNDED;
        $this->save();

        return $this;
    }

    public function refundRecords()
    {
        return $this->hasMany(ApRefundRecord::class);
    }

    public function refundRecordsRefunded()
    {
        return $this->refundRecords()->where('status', ApRefundRecord::STATUS_REFUNDED);
    }
}
