<?php

namespace Modules\Socialite\Http\Controllers;

use Modules\Socialite\Platforms\WechatThirdParty;

trait WechatControllerTrait
{
    /**
     * 开放平台第三方平台授权事件接收URL.
     * /callback/wechat/serve.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function serve()
    {
        return (new WechatThirdParty())->openPlatform->server->serve();
    }

    /**
     * 开放平台第三方平台授权事件.
     * /callback/wechat/event/$APPID$.
     *
     * @param $appId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException|\Exception
     */
    public function event($appId)
    {
        $server = (new WechatThirdParty())->appIdToApp($appId)->server;

        $server->push(function () {
            return 'Welcome!';
        });

        return $server->serve();
    }

    /**
     * /callback/wechat/pre-authorization
     * 开放平台第三方平台申请授权链接.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function preAuthorization()
    {
        $openPlatform = (new WechatThirdParty())->openPlatform;

        if (request('auth_code')) {
            $res = $openPlatform->handleAuthorize(request('auth_code'));

            if (!empty($res['authorization_info'])) {
                return response('授权成功');
            }

            abort(403, '授权失败: '.json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        $url = $openPlatform->getPreAuthorizationUrl(request()->fullUrl());
//        $url = $openPlatform->getMobilePreAuthorizationUrl(request()->fullUrl());

        return response("<script>window.location.href = '{$url}';</script>");
    }
}
