<?php

namespace Modules\Socialite\Platforms;

class WechatApp implements FactoryInterface
{
    use WechatPublicTrait;

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
        $config = array_only(config('socialite.wechat.app'), [
            'app_id',
            'secret',
        ]);
        $this->officialAccount = \EasyWeChat\Factory::officialAccount($config);
    }

    /**
     * @return bool|mixed
     *
     * @throws \Exception
     */
    public function handle()
    {
        if (!request('code')) {
            abort(400, '缺失 code');
        }

        $oauth = $this->officialAccount->oauth;

        $this->user = $oauth->user($oauth->getAccessToken(request('code')));

        $original = $this->user->getOriginal();

        if (isset($original['errcode'])) {
            throw new \Exception(json_encode($original, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        return true;
    }
}
