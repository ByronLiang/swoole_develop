<?php

namespace Modules\Socialite\Platforms;

class Factory
{
    /**
     * @var string
     */
    protected $provider;

    /**
     * @var FactoryInterface
     */
    public $platform;

    public function __construct(string $provider = null)
    {
        switch ($provider) {
            case 'wechat_public':
                $this->platform = (new WechatThirdPartyPublic());
//                $this->platform = (new WechatPublic());
                break;
            case 'wechat_applet':
                $this->platform = (new WechatThirdPartyApplet());
//                $this->platform = (new WechatApplet());
                break;
            case 'wechat_qr':
                $this->platform = (new WechatQrCode());
                break;
            case 'wechat_app':
                $this->platform = (new WechatApp());
                break;
        }

        if (!$this->platform instanceof FactoryInterface) {
            abort(500, '不支持此登录模式');
        }

        $this->provider = $provider;
    }

    /**
     * @return mixed|\Modules\Socialite\Entities\Socialite
     *
     * @throws \Exception
     */
    public function handle()
    {
        $res = $this->platform->handle();

        if ($res instanceof \Symfony\Component\HttpFoundation\RedirectResponse) {
            return $res;
        }

        if (is_bool($res) && !$res) {
            return $res;
        }

        return $this->platform->socialite($this->provider);
    }

    public function getRequestHandle($base_path = '/')
    {
        $cookie_key = md5(request()->userAgent().request()->getClientIp().__METHOD__);
        $return = request('return');
        $cache_key = request()->cookie($cookie_key);

        if ($cache_key && !$return && \Cache::has($cache_key)) {
            request()->merge(\Cache::get($cache_key));

            return $this;
        }

        $cache_key = $cookie_key.uniqid();

        $return = $return ?: request()->header('referer');
        $return = $return ?: $base_path;
        request()->merge(compact('return'));

//        cookie()->queue(cookie()->make($cookie_key, $cache_key, 10)); // 当使用web中间件时使用，TODO: 缺少判断是否启用web中间件
        setrawcookie($cookie_key, $cache_key, time() + 60 * 10, '/');

        \Cache::put($cache_key, request()->all(), 10);

        return $this;
    }
}
