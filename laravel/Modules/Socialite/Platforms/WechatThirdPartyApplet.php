<?php

namespace Modules\Socialite\Platforms;

class WechatThirdPartyApplet extends WechatThirdParty implements FactoryInterface
{
    use WechatAppletTrait;

    public function __construct()
    {
        parent::__construct();

        $this->miniProgram();
    }
}
