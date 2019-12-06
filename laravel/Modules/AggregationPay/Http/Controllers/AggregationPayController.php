<?php

namespace Modules\AggregationPay\Http\Controllers;

use Modules\AggregationPay\AggregationPay;
use Illuminate\Routing\Controller;

class AggregationPayController extends Controller
{
    /**
     * @param $channel
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function postHook($channel)
    {
        return (new AggregationPay())->payHook($channel);
    }
}
