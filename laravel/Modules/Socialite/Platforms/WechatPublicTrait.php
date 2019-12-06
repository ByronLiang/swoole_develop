<?php

namespace Modules\Socialite\Platforms;

/**
 * Trait WechatPublicTrait.
 *
 * @mixin WechatThirdParty|WechatPublic
 */
trait WechatPublicTrait
{
    use WechatSocialiteTrait;

    /**
     * @var \Overtrue\Socialite\User
     */
    protected $user;

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle()
    {
        $oauth = $this->officialAccount->oauth;

        if (!request('state')) {
            return $oauth->scopes(['snsapi_userinfo'])->redirect(request()->url());
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
