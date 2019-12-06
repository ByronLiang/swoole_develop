<?php

namespace Modules\AggregationPay\Services;

use Ganguo\AlipayAop\AopClient;
use Ganguo\AlipayAop\request\AlipayFundTransOrderQueryRequest;
use Ganguo\AlipayAop\request\AlipayFundTransToaccountTransferRequest;
use Ganguo\AlipayAop\request\AlipayTradeAppPayRequest;
use Ganguo\AlipayAop\request\AlipayTradeFastpayRefundQueryRequest;
use Ganguo\AlipayAop\request\AlipayTradePagePayRequest;
use Ganguo\AlipayAop\request\AlipayTradeQueryRequest;
use Ganguo\AlipayAop\request\AlipayTradeRefundRequest;
use Ganguo\AlipayAop\request\AlipayTradeWapPayRequest;

class AlipayService
{
    /**
     * @var self
     */
    private static $instance;
    public static $type;

    /**
     * @param string $type
     *
     * @return self
     */
    public static function getInstance($type = '')
    {
        if (empty($type)) {
            $type = config('aggregation_pay.alipay_default');
        }
        if (!static::$instance || static::$type != $type) {
            $config = config('aggregation_pay.alipay.'.$type);
            static::$instance = new static(
                $config['app_id'],
                $config['alipay_public_key_path'],
                $config['app_private_key_path']
            );
            if ($config['sandbox']) {
                static::$instance->sandbox();
            }
            if ($config['rsa2']) {
                static::$instance->RSA2();
            }
        }

        return static::$instance;
    }

    private $aop;
    private $notify_url;

    public function __construct($app_id, $alipay_public_key_path, $app_private_key_path)
    {
        $aop = new AopClient();
        $aop->appId = $app_id;
        $aop->rsaPrivateKey = $this->getRasData($app_private_key_path);
        $aop->alipayrsaPublicKey = $this->getRasData($alipay_public_key_path);

        $aop->debugInfo = env('APP_DEBUG', false);

        $this->aop = $aop;
    }

    private function getRasData($path)
    {
        $data = preg_replace('/---.+---/', '', file_get_contents($path));
        $data = str_replace("\n", '', $data);

        return $data;
    }

    /**
     * @param \Ganguo\AlipayAop\AlipayRequestInterface|object $request
     * @param array                                           $bizContent
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    private function executeBizContent($request, array $bizContent)
    {
        $request->setBizContent(json_encode(array_filter($bizContent)));
        $result = $this->aop->execute($request);

        if (!$result) {
            throw new \Exception('支付宝sdk执行错误');
        }

        $responseNode = str_replace('.', '_', $request->getApiMethodName()).'_response';
        $response_result = $result->$responseNode;
        if (!empty($response_result->code) && 10000 == $response_result->code) {
            return $response_result;
        }
        \Log::error(self::class.':'.get_class($request).'请求失败', (array) $response_result);

        return $response_result;
    }

    public function setNotifyUrl($url)
    {
        $this->notify_url = $url;

        return $this;
    }

    public function sandbox()
    {
        $this->aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';

        return $this;
    }

    public function RSA2()
    {
        $this->aop->signType = 'RSA2';

        return $this;
    }

    /**
     * 手机网站支付:https://docs.open.alipay.com/203/107090/.
     *
     * @param string      $subject         订单标题最多256个字
     * @param string      $out_trade_no    商户网站唯一订单号
     * @param float       $total_amount    订单金额
     * @param string      $return_url      返回链接
     * @param string|null $body            订单具体描述最多128个字
     * @param string|null $timeout_express 允许的最晚付款时间,1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）
     *
     * @return string
     *
     * @throws \Exception
     */
    public function tradeWapPay($subject, $out_trade_no, $total_amount, $return_url, $body = null, $timeout_express = null)
    {
        $request = new AlipayTradeWapPayRequest();

        $bizContent = [
            'subject' => $subject,
            'out_trade_no' => $out_trade_no,
            'total_amount' => $total_amount,
            'product_code' => 'QUICK_WAP_PAY',
            'body' => $body,
            'timeout_express' => $timeout_express,
        ];
        $request->setReturnUrl($return_url);
        $request->setNotifyUrl($this->notify_url);

        $request->setBizContent(json_encode(array_filter($bizContent)));
        $result = $this->aop->pageExecute($request, 'get');

        return $result;
    }

    /**
     * App支付:https://docs.open.alipay.com/204/105465/.
     *
     * @param string      $subject
     * @param string      $out_trade_no
     * @param float       $total_amount
     * @param string|null $body
     * @param string|null $timeout_express
     *
     * @return string
     */
    public function tradeAppPay($subject, $out_trade_no, $total_amount, $body = null, $timeout_express = null)
    {
        $request = new AlipayTradeAppPayRequest();

        $bizContent = [
            'subject' => $subject,
            'out_trade_no' => $out_trade_no,
            'total_amount' => $total_amount,
            'product_code' => 'QUICK_MSECURITY_PAY',
            'body' => $body,
            'timeout_express' => $timeout_express,
        ];

        $request->setNotifyUrl($this->notify_url);

        $request->setBizContent(json_encode(array_filter($bizContent)));
        $result = $this->aop->sdkExecute($request);

        return $result;
    }

    /**
     * 电脑网站支付:https://docs.open.alipay.com/270/alipay.trade.page.pay/.
     *
     * @param string $subject
     * @param string $out_trade_no
     * @param float  $total_amount
     * @param $return_url
     * @param string|null $body
     * @param string|null $timeout_express
     *
     * @return string
     *
     * @throws \Exception
     */
    public function tradePagePay($subject, $out_trade_no, $total_amount, $return_url, $body = null, $timeout_express = null)
    {
        $request = new AlipayTradePagePayRequest();

        $bizContent = [
            'subject' => $subject,
            'out_trade_no' => $out_trade_no,
            'total_amount' => $total_amount,
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
            'body' => $body,
            'timeout_express' => $timeout_express,
        ];
        $request->setReturnUrl($return_url);
        $request->setNotifyUrl($this->notify_url);

        $request->setBizContent(json_encode(array_filter($bizContent)));
        $result = $this->aop->pageExecute($request, 'get');

        return $result;
    }

    /**
     * 异步回调通知处理.
     *
     * @param array $post
     *
     * @return bool
     */
    public function notify(array $post)
    {
        return $this->aop->rsaCheckV1($post, null);
    }

    /**
     * 统一收单线下交易查询:https://docs.open.alipay.com/api_1/alipay.trade.query.
     *
     * @param string      $out_trade_no 商户订单号
     * @param string|null $trade_no     支付宝交易号
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function tradeQuery($out_trade_no, $trade_no = null)
    {
        $request = new AlipayTradeQueryRequest();

        $bizContent = [
            'out_trade_no' => $out_trade_no,
            'trade_no' => $trade_no,
        ];

        return $this->executeBizContent($request, $bizContent);
    }

    /**
     * 统一收单交易退款接口:https://docs.open.alipay.com/api_1/alipay.trade.refund.
     *
     * @param string      $out_trade_no   商户订单号
     * @param float       $refund_amount  退款的金额，该金额订单金额
     * @param string      $out_request_no 退款单号,同一笔交易多次退款需要保证唯一
     * @param string|null $refund_reason  退款的原因说明
     * @param string|null $trade_no       支付宝交易号
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function tradeRefund($out_trade_no, $refund_amount, $out_request_no, $refund_reason = null, $trade_no = null)
    {
        $request = new AlipayTradeRefundRequest();

        $bizContent = [
            'out_trade_no' => $out_trade_no,
            'trade_no' => $trade_no,
            'refund_amount' => $refund_amount,
            'out_request_no' => $out_request_no,
            'refund_reason' => $refund_reason,
        ];

        return $this->executeBizContent($request, $bizContent);
    }

    /**
     * 统一收单交易退款查询:https://docs.open.alipay.com/api_1/alipay.trade.fastpay.refund.query/.
     *
     * @param string      $out_trade_no   商户订单号
     * @param string      $out_request_no 商户退款单号
     * @param string|null $trade_no       支付宝交易号
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function tradeFastpayRefundQuery($out_trade_no, $out_request_no, $trade_no = null)
    {
        $request = new AlipayTradeFastpayRefundQueryRequest();

        $bizContent = [
            'out_trade_no' => $out_trade_no,
            'trade_no' => $trade_no,
            'out_request_no' => $out_request_no,
        ];

        return $this->executeBizContent($request, $bizContent);
    }

    /**
     * 单笔转账到支付宝账户接口:https://doc.open.alipay.com/docs/api.htm?apiId=1321&docType=4.
     *
     * @param string $out_biz_no      发起转账的转账单据号
     * @param string $payee_account   收款登陆账号
     * @param float  $amount          金额
     * @param string $payee_real_name 收款人真实姓名
     * @param string $remark          备注
     * @param string $payer_show_name 付款信息
     *
     * @return bool|mixed
     *
     * @throws \Exception
     */
    public function fundTransToaccountTransfer($out_biz_no, $payee_account, $amount, $payee_real_name = null, $remark = null, $payer_show_name = null)
    {
        $request = new AlipayFundTransToaccountTransferRequest();

        $bizContent = [
            'out_biz_no' => $out_biz_no,
            'payee_type' => 'ALIPAY_LOGONID',
            'payee_account' => $payee_account,
            'amount' => $amount,
            'payer_show_name' => $payer_show_name,
            'payee_real_name' => $payee_real_name,
            'remark' => $remark,
        ];

        return $this->executeBizContent($request, $bizContent);
    }

    /**
     * 查询转账订单接口:https://doc.open.alipay.com/docs/api.htm?docType=4&apiId=1322.
     *
     * @param string      $out_biz_no 发起转账的转账单据号
     * @param string|null $order_id   支付宝转账单据号
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function fundTransOrderQuery($out_biz_no, $order_id = null)
    {
        $request = new AlipayFundTransOrderQueryRequest();

        $bizContent = [
            'out_biz_no' => $out_biz_no,
            'order_id' => $order_id,
        ];

        return $this->executeBizContent($request, $bizContent);
    }
}
