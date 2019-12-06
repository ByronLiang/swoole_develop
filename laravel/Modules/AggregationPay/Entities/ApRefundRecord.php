<?php

namespace Modules\AggregationPay\Entities;

use Modules\AggregationPay\Channels\Factory;
use Modules\AggregationPay\Events\AggregationPayEvent;

/**
 * @property string          $status
 * @property float           $amount
 * @property ApPaymentRecord $paymentRecord
 */
class ApRefundRecord extends \App\Models\Model
{
    const STATUS_REFUNDING = 'refunding'; // 退款中
    const STATUS_REFUNDED = 'refunded'; // 已退款
    const STATUS_FAILURE = 'failure'; // 失败

    protected $casts = [
        'failure_reason' => 'object',
    ];

    protected $hidden = [
        'able_id',
        'able_type',
        'failure_reason',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $self) {
            $paymentRecord = $self->paymentRecord;
            if (ApPaymentRecord::STATUS_PAID != $paymentRecord->status) {
                abort(500, '创建退款记录失败，非付款记录');
            }
            $can_refund_amount = (float) bcsub($paymentRecord->amount, $paymentRecord->refund_amount, 2);
            if (!$self->amount) {
                $self->amount = $can_refund_amount;
            }
            if (0 >= $self->amount || $self->amount > $can_refund_amount) {
                abort(500, '无可退款金额: '.$paymentRecord->id);
            }

            if (!$self->able_id || !$self->able_type) {
                $self->able_id = $paymentRecord->able_id;
                $self->able_type = $paymentRecord->able_type;
            }
        });
        static::created(function (self $model) {
            $model->initiateRefund();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|self
     */
    public function able()
    {
        return $this->morphTo();
    }

    public function statusToRefunded()
    {
        if (self::STATUS_REFUNDED == $this->status) {
            return $this;
        }
        $this->status = self::STATUS_REFUNDED;
        $this->save();

        event(new AggregationPayEvent($this));

        return $this;
    }

    public function statusToFailure($failure_reason = [])
    {
        if (!is_string($failure_reason)) {
            $failure_reason = json_encode($failure_reason, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $this->status = self::STATUS_FAILURE;
        $this->failure_reason = $failure_reason;
        $this->save();

        return $this;
    }

    /**
     * 发起退款操作.
     *
     * @return $this|ApRefundRecord
     *
     * @throws \Exception
     */
    public function initiateRefund()
    {
        if (self::STATUS_REFUNDED == $this->status) {
            return $this;
        }
        if ($this->paymentRecord->amount < $this->amount) {
            $this->amount = $this->paymentRecord->amount;
        }
        if (!$this->refund_no) {
            $this->refund_no = date('YmdHis').$this->id;
            $this->save();
        }
        $channel = $this->paymentRecord->channel;

        if ($this->amount < 0.01) {
            return $this->statusToRefunded();
        }

        if ('test' === $channel) {
            return $this->statusToRefunded();
        }

        $factory = new Factory($channel);
        try {
            $factory->refundCreate(
                $this->paymentRecord->payment_no,
                $this->paymentRecord->amount,
                $this->refund_no,
                $this->amount,
                $this->remark
            );
        } catch (\Exception $exception) {
            $this->statusToFailure($exception->getMessage());

            throw new $exception();
        }

        return $this->statusToRefunded();
    }

    public function paymentRecord()
    {
        return $this->belongsTo(ApPaymentRecord::class);
    }
}
