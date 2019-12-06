<?php

namespace App\Services;

use Log;
use Config;
use GuzzleHttp\Client;

class LeancloudService
{
    const EXPIRATION_INTERVAL = 86400;

    private $url;
    private $appId;
    private $appKey;
    private $masterKey;
    private $prod;
    private $action;
    private $client;

    public function __construct()
    {
        $cfg = Config::get('services.leancloud');
        $this->url = $cfg['url'];
        $this->appId = $cfg['appid'];
        $this->appKey = $cfg['appkey'];
        $this->masterKey = $cfg['masterkey'];
        $this->prod = is_bool($cfg['prod']) ? ($cfg['prod'] ? 'prod' : 'dev') : $cfg['prod'];
        $this->action = $cfg['action'];
        $this->client = new Client([
            'allow_redirects' => false,
            'http_errors' => false,
            'base_uri' => $this->url,
            'headers' => [
                'User-Agent' => 'Funshow (ganguo)',
                'X-LC-Id' => $this->appId,
                'X-LC-Key' => $this->masterKey ? ($this->masterKey.',master') : $this->appKey,
            ],
            'timeout' => '60',
        ]);
    }

    private function handleResponse($response)
    {
        if (2 != substr($response->getStatusCode(), 0, 1)) {
            $message = (string) $response->getBody();
            error_log($message);
            Log::error($message);

            return false;
        }

        return true;
    }

    public function requestSmsCode($phone)
    {
        $response = $this->client->post('requestSmsCode', [
            'json' => ['mobilePhoneNumber' => $phone],
        ]);

        return $this->handleResponse($response);
    }

    public function verifySmsCode($phone, $code)
    {
        $response = $this->client->post('verifySmsCode/'.$code.'?mobilePhoneNumber='.$phone, [
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        return $this->handleResponse($response);
    }

    // https://leancloud.cn/docs/push_guide.html#推送消息
    public function push(array $body)
    {
        return $this->handleResponse($this->client->post('push', ['json' => $body]));
    }

    public function pushUserId($userId, array $data, $expiration_interval = null, $push_time = null)
    {
        $data['badge'] = 'Increment';
        $data['action'] = $this->action;
        $body = [
            'where' => ['userid' => ''.$userId],
            'prod' => $this->prod,
            'data' => $data,
            'expiration_interval' => $expiration_interval ?: self::EXPIRATION_INTERVAL,
        ];
        if ($push_time) {
            $body['push_time'] = $push_time;
        }

        return $this->push($body);
    }

    public function pushChannels($channels, array $data, $expiration_interval = null, $push_time = null)
    {
        if (!is_array($channels)) {
            $channels = [$channels];
        }
        $data['badge'] = 'Increment';
        $data['action'] = $this->action;
        $body = [
            'channels' => $channels,
            'prod' => $this->prod,
            'data' => $data,
            'expiration_interval' => $expiration_interval ?: self::EXPIRATION_INTERVAL,
        ];
        if ($push_time) {
            $body['push_time'] = $push_time;
        }

        return $this->push($body);
    }
}
