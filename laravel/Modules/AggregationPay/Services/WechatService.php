<?php

namespace Modules\AggregationPay\Services;

use EasyWeChat\Factory;

class WechatService
{
    private static $_instance = null;

    public static function getInstance($type = '')
    {
        if (empty($type)) {
            $type = config('aggregation_pay.wechat_default');
        }
        if (is_null(self::$_instance) || isset(self::$_instance)) {
            $config = config('aggregation_pay.wechat.'.$type);
            $config = array_merge([
                'debug' => env('APP_DEBUG', false),
                'log' => [
                    'level' => 'debug',
                    'file' => storage_path('logs/wechat-'.date('Y-m-d').'.log'),
                ],
                'payment' => [
                    'notify_url' => '',
                ],
                'response_type' => 'array',
//                'http' => [
//                    'proxy' => 'socks5://127.0.0.1:9991'
//                ]
            ], $config);
            self::$_instance = new static($config);
        }

        return self::$_instance;
    }

    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function payment()
    {
        return Factory::payment($this->config);
    }

    public function setNotifyUrl($url)
    {
        $this->config['notify_url'] = $url;

        return $this;
    }

    /**
     * 生成预付订单.
     *
     * @param string      $out_trade_no
     * @param float       $total_fee
     * @param string      $body
     * @param string|null $detail
     * @param string      $trade_type
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function paymentConfig($out_trade_no, $total_fee, $body, $detail = null, $trade_type = 'JSAPI')
    {
        $payment = self::payment();

        $cache_key = $out_trade_no.$trade_type;
        if (\Cache::has($cache_key)) {
            $result = \Cache::get($cache_key);
        } else {
            if ('JSAPI' == $trade_type) {
                $openid = session('wechat.oauth_id');
                if (!$openid) {
                    abort(403, '缺失open_id');
                }
            } else {
                $openid = '';
            }

            $product_id = $out_trade_no;
            $total_fee *= 100;
            $attributes = compact(
                'openid',
                'product_id',
                'out_trade_no',
                'total_fee',
                'body',
                'detail',
                'trade_type'
            );

            $result = $payment->order->unify(array_filter($attributes));

            if ('SUCCESS' == $result['return_code'] && 'SUCCESS' == $result['result_code']) {
                $minutes = 'MWEB' == $result['trade_type'] ? 5 : 120;
                \Cache::put($cache_key, $result, $minutes);
            } else {
                throw new \Exception('发起微信支付失败:'.json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }

        $jssdk = $payment->jssdk;
        switch ($result['trade_type']) {
            case 'JSAPI':
                $config = $jssdk->bridgeConfig($result['prepay_id'], false);
//                $config = $jssdk->sdkConfig($result['prepay_id']);
                break;
            case 'APP':
                $config = $jssdk->appConfig($result['prepay_id']);
                break;
            case 'NATIVE':
                $config = $result;
                break;
            case 'MWEB':
                $config = $result['mweb_url'];
                break;
            default:
                throw new \Exception('错误微信支付类型');
                break;
        }

        return $config;
    }

    /**
     * 发起退款.
     *
     * @param $orderNo $orderNo 订单编号
     * @param $refundNo $refundNo 退款编号
     * @param $totalFee $totalFee 订单金额
     * @param null $refundFee
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function refund($orderNo, $refundNo, $totalFee, $refundFee = null)
    {
        $totalFee = (int) ($totalFee * 100);
        if ($refundFee) {
            $refundFee = (int) ($refundFee * 100);
        }
        if ($refundFee > $totalFee) {
            $refundFee = $totalFee;
        }

        $payment = self::payment();

        $result = $payment->refund->byOutTradeNumber($orderNo, $refundNo, $totalFee, $refundFee);
        if ('FAIL' == $result['result_code'] && 'NOTENOUGH' == $result['err_code']) {
            $result = $payment->refund->byOutTradeNumber($orderNo, $refundNo, $totalFee, $refundFee, [
                'refund_account' => 'REFUND_SOURCE_RECHARGE_FUNDS',
            ]);
        }

        return $result;
    }
}
