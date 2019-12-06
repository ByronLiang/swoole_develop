<?php

namespace Modules\Socialite\Platforms;

/**
 * Trait WechatAppletTrait.
 *
 * @mixin WechatThirdParty|WechatApplet
 */
trait WechatAppletTrait
{
    use WechatSocialiteTrait;

    /**
     * @var \Overtrue\Socialite\User
     */
    protected $user;

    /**
     * @return bool
     *
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function handle()
    {
        if (!request('code')) {
            abort(400, '缺失 code');
        }

        $auth = $this->miniProgram->auth;
        $encryptor = $this->miniProgram->encryptor;

        $res = $auth->session(request('code'));
        if (empty($res['openid'])) {
            abort(400, '无效 code');
        }
        if (empty($res['session_key'])) {
            abort(400, '缺失 session key');
        }

        if (empty(request('iv')) || empty(request('encryptedData'))) {
            abort(400, '缺失 iv 或 encryptedData');
        }

        $res = $encryptor->decryptData(
            $res['session_key'],
            request('iv'),
            request('encryptedData')
        );

        $this->user = $res;

        return true;
    }

    public function getAttributes()
    {
        $user = $this->user;

        return [
            'unique_id' => $user['openId'] ?? '',
            'avatar' => $user['avatarUrl'] ?? '',
            'nickname' => $user['nickName'] ?? '',
            'extend' => $user,
        ];
    }
}
