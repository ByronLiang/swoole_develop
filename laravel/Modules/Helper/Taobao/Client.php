<?php

namespace Ganguo\Taobao;

class Client
{
    public $app_key;

    public $secret_key;

    public $gateway_url;

    public function __construct($app_key = '', $secret_key = '')
    {
        $this->app_key = $app_key;
        $this->secret_key = $secret_key;
        $this->setGatewayUrl();
    }

    final public function setGatewayUrl($gateway_url = 'http://gw.api.taobao.com/router/rest')
    {
        $this->gateway_url = $gateway_url;

        return $this;
    }

    final protected function generateSign($params)
    {
        ksort($params);

        $stringToBeSigned = $this->secret_key;
        foreach ($params as $k => $v) {
            if (is_string($v) && '@' != substr($v, 0, 1)) {
                $stringToBeSigned .= $k.$v;
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secret_key;

        return strtoupper(md5($stringToBeSigned));
    }

    final public function post($method, $api_params, $session = null)
    {
        $sys_params = array_filter([
            'method' => $method,
            'app_key' => $this->app_key,
            'sign_method' => 'md5',
            'sign' => null,
            'session' => $session,
            'format' => 'json',
            'v' => '2.0',
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
        $post_data = array_merge($api_params, $sys_params);
        $post_data['sign'] = $this->generateSign($post_data);

        $res = (new \GuzzleHttp\Client())
            ->post($this->gateway_url, [
                'form_params' => $post_data,
            ])
            ->getBody()
            ->getContents();

        return json_decode($res);
    }
}
