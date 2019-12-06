<?php

namespace Modules\Socialite\Platforms;

class WechatThirdPartyPublic extends WechatThirdParty implements FactoryInterface
{
    use WechatPublicTrait;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->officialAccount();
    }
}
