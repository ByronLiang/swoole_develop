<?php

namespace Modules\Socialite\Platforms;

class WechatPublic implements FactoryInterface
{
    use WechatPublicTrait;

    /**
     * @var \EasyWeChat\OfficialAccount\Application
     */
    public $officialAccount;

    public function __construct()
    {
        $config = array_only(config('socialite.wechat.public'), [
            'app_id',
            'secret',
        ]);
        $this->officialAccount = \EasyWeChat\Factory::officialAccount($config);
    }
}
