<?php

namespace Modules\Socialite\Platforms;

class WechatApplet implements FactoryInterface
{
    use WechatAppletTrait;

    /**
     * @var \EasyWeChat\MiniProgram\Application
     */
    public $miniProgram;

    public function __construct()
    {
        $config = array_only(config('socialite.wechat.applet'), [
            'app_id',
            'secret',
        ]);
        $this->miniProgram = \EasyWeChat\Factory::miniProgram($config);
    }
}
