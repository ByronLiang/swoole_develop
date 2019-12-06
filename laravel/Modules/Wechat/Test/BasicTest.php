<?php

namespace App\Modules\Message\Tests;

use Tests\TestCase;
use Modules\Wechat\Entities\Wechat;

class BasicTest extends TestCase
{
    public $wechat;
    public $app;

    public function testBasicTest()
    {
        if (!$this->wechat) {
            $this->wechat = new Wechat();
        }
        $this->app = $this->wechat->getApplication();
        $ips = $this->app->base->getValidIps();
        dd($ips);
    }
}
