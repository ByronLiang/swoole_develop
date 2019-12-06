<?php

namespace Modules\Wechat\Http\Controllers;

use Modules\Wechat\Entities\Wechat;
use Modules\Wechat\Handles\MessageHandle;
use Illuminate\Routing\Controller;

class WechatController extends Controller
{
    private $wechat;
    private $app;

    public function __construct()
    {
        if (!$this->wechat) {
            $this->wechat = new Wechat();
        }
        $this->app = $this->wechat->getApplication();
    }

    public function index()
    {
        $this->app->server->push(MessageHandle::handle($this->app));

        return $this->app->server->serve();
    }

    public function showQr()
    {
        $weChatFlag = strtotime('now').rand(1, 9999);
        $qrCode = $this->app->qrcode;
        $result = $qrCode->temporary($weChatFlag, 3600 * 24);
        $url = $qrCode->url($result['ticket']);

        return \Response::success(compact('url'));
    }
}
