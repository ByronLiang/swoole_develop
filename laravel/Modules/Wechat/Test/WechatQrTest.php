<?php

namespace App\Modules\Message\Tests;

use Tests\TestCase;
use Modules\Wechat\Entities\Wechat;

class WechatQrTest extends TestCase
{
    public $wechat;
    public $app;

    public function testBasicTest()
    {
        if (!$this->wechat) {
            $this->wechat = new Wechat();
        }
        $this->app = $this->wechat->getApplication();
        $weChatFlag = md5(rand(1, 100).strtotime('now'));
        $qrCode = $this->app->qrcode;
        $result = $qrCode->temporary($weChatFlag, 3600 * 24);
        $url = $qrCode->url($result['ticket']);
        dd(compact('url', 'weChatFlag'));
    }
}
