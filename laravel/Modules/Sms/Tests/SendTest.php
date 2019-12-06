<?php

namespace Modules\Sms\Tests;

use Tests\TestCase;

class SendTest extends TestCase
{
    /**
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function testExample()
    {
        $phone = '';
        $res = (new \Modules\Sms\Captcha())->send($phone);
//        $res = (new \Modules\Sms\Captcha())->check($phone, 2186);
        dd($res);
    }
}
