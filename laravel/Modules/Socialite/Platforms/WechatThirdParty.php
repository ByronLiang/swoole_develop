<?php

namespace Modules\Socialite\Platforms;

class WechatThirdParty
{
    /**
     * @var \EasyWeChat\OpenPlatform\Application
     */
    public $openPlatform;

    /**
     * @var \EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
     */
    public $officialAccount;

    /**
     * @var \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
     */
    public $miniProgram;

    private $config;

    public function __construct(array $config = [])
    {
        $this->config = config('socialite.wechat.third_party');

        $config = array_merge(array_only($this->config, [
            'app_id',
            'secret',
            'token',
            'aes_key',
        ]), $config);

        $this->openPlatform = \EasyWeChat\Factory::openPlatform($config);
    }

    /**
     * @param string $sub
     *
     * @return $this
     */
    public function officialAccount($sub = 'default')
    {
        list($appid, $refresh_token) = array_values($this->getAuthorizer($sub, 'public'));

        $this->officialAccount = $this->openPlatform->officialAccount($appid, $refresh_token);

        return $this;
    }

    /**
     * @param string $sub
     *
     * @return $this
     */
    public function miniProgram($sub = 'default')
    {
        list($appid, $refresh_token) = array_values($this->getAuthorizer($sub, 'applet'));

        $this->miniProgram = $this->openPlatform->miniProgram($appid, $refresh_token);

        return $this;
    }

    /**
     * 获取授权数据.
     *
     * @param string $sub
     * @param string $type public|applet
     *
     * @return array
     */
    protected function getAuthorizer(string $sub, string $type): array
    {
        $authorizer = $this->openPlatform->getAuthorizer(array_get($this->config, "sub.{$type}.{$sub}"));

        if (empty($authorizer['authorization_info'])) {
            abort(500, json_encode($authorizer));
        }

        return [
            'appid' => $authorizer['authorization_info']['authorizer_appid'],
            'refresh_token' => $authorizer['authorization_info']['authorizer_refresh_token'],
        ];
    }

    /**
     * 1. php artisan tinker
     * 2. (new \Modules\Socialite\Platforms\WechatThirdParty())->getTicket().
     *
     * @return string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTicket()
    {
        /**
         * @var \EasyWeChat\OpenPlatform\Auth\VerifyTicket
         */
        $verify_ticket = $this->openPlatform->verify_ticket;

        return $verify_ticket->getTicket();
    }

    /**
     * @param $ticket
     *
     * @return \EasyWeChat\OpenPlatform\Auth\VerifyTicket
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setTicket($ticket)
    {
        /**
         * @var \EasyWeChat\OpenPlatform\Auth\VerifyTicket
         */
        $verify_ticket = $this->openPlatform->verify_ticket;

        return $verify_ticket->setTicket($ticket);
    }

    /**
     * @param string $appId
     *
     * @return \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application|\EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
     *
     * @throws \Exception
     */
    public function appIdToApp(string $appId)
    {
        if (in_array($appId, (array) array_get($this->config, 'sub.public'))) {
            return $this->openPlatform->officialAccount($appId);
        }
        if (in_array($appId, (array) array_get($this->config, 'sub.applet'))) {
            return $this->openPlatform->miniProgram($appId);
        }
        throw new \Exception('none appId');
    }

    /**
     * @param string $appId
     * @param $callback
     * @param string $condition
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException|\Exception
     */
    public function eventSub(string $appId, $callback, string $condition = '*')
    {
        if (in_array($appId, (array) array_get($this->config, 'sub.public'))) {
            $server = $this->openPlatform->officialAccount($appId)->server;
        } elseif (in_array($appId, (array) array_get($this->config, 'sub.applet'))) {
            $server = $this->openPlatform->miniProgram($appId)->server;
        } else {
            throw new \Exception('none appId');
        }

        $server->push($callback, $condition);

        return $server->serve();
    }
}
