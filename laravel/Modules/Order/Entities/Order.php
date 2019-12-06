<?php

namespace Modules\Order\Entities;

use App\Models\User;

class Order extends \App\Models\Model implements \Modules\AggregationPay\AggregationPayInterface
{
    use \Modules\AggregationPay\AggregationPayTrait;
    use \EloquentFilter\Filterable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    const WAIT_PAY = 'wait_pay';
    const CANCEL = 'cancel';
    const CLOSE = 'close';
    const WAIT_SEND = 'wait_send';
    const WAIT_RECEIVE = 'wait_receive';
    const WAIT_COMMENT = 'wait_comment';
    const SUCCESS = 'success';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
