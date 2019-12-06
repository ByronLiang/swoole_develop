<?php

namespace Modules\Socialite\Platforms;

class WechatQrCode implements FactoryInterface
{
    use WechatSocialiteTrait;

    /**
     * @var \EasyWeChat\OfficialAccount\Application
     */
    public $officialAccount;

    /**
     * @var \Overtrue\Socialite\User
     */
    protected $user;

    public function __construct()
    {
        $config = array_only(config('socialite.wechat.qrcode'), [
            'app_id',
            'secret',
        ]);
        $this->officialAccount = \EasyWeChat\Factory::officialAccount($config);
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle()
    {
        $oauth = $this->officialAccount->oauth;

        if (!request('state')) {
            return $oauth->scopes(['snsapi_login'])->redirect(request()->url());
        }

        if (!request('code')) {
            return false;
        }

        $this->user = $oauth->user();

        $original = $this->user->getOriginal();

        if (isset($original['errcode'])) {
            throw new \Exception(json_encode($original, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        return true;
    }
}
