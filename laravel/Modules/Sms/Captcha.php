<?php

namespace Modules\Sms;

class Captcha
{
    protected $template;
    protected $captcha;
    protected $sms;

    public function __construct()
    {
        $this->template = config('sms.template_id.captcha');
        $this->sms = new Sms();
    }

    /**
     * 发送短信
     *
     * @param $phone
     *
     * @return array|null
     *
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function send($phone)
    {
        if ($this->sms->disabled) {
            return null;
        }

        try {
            return $res = $this->sms->send($phone, $this->message($phone));
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $exception = array_first($exception->results)['exception'];
            if ($exception instanceof \GuzzleHttp\Exception\ClientException) {
                abort(500, $exception->getMessage());
            }
            abort(500, json_encode($exception, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @param $phone
     *
     * @return array
     */
    private function message($phone)
    {
        return [
            'content' => $this->content($phone),
            'template' => $this->template,
            'data' => $this->data($phone),
        ];
    }

    /**
     * 短信变量.
     *
     * @param $phone
     *
     * @return array
     */
    private function data($phone)
    {
        return ['captcha' => $this->captcha($phone)];
    }

    /**
     * 短信内容.
     *
     * @param $phone
     *
     * @return string
     */
    private function content($phone)
    {
        return '您的验证码为: '.$this->captcha($phone);
    }

    /**
     * 生成验证码
     *
     * @param $phone
     * @param bool $is_pull
     *
     * @return bool|int|mixed
     */
    public function captcha($phone, $is_pull = false)
    {
        $cache_key = self::class.__FUNCTION__.'_'.$phone;
        if ($is_pull) {
            if (!\Cache::has($cache_key)) {
                return false;
            }

            return \Cache::get($cache_key);
        }
        if ($this->captcha) {
            return $this->captcha;
        }

        $this->captcha = \Cache::remember($cache_key, 10, function () {
            return rand(111111, 999999);
        });

        return $this->captcha;
    }

    /**
     * 检查验证码
     *
     * @param $phone
     * @param $captcha
     *
     * @return bool
     */
    public function check($phone, $captcha)
    {
        if ($this->sms->disabled) {
            return true;
        }

        return (string) $this->captcha($phone, true) === (string) $captcha;
    }
}
