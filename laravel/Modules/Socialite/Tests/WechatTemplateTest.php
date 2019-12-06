<?php

namespace Modules\Socialite\Tests;

use Modules\Socialite\Platforms\WechatThirdParty;
use Tests\TestCase;

class WechatTemplateTest extends TestCase
{
    //    private $ticket = 'ticket@@@PJ7qTP_vH9hvnhVAkhzrk6xQYELN_R7L2TglMfisaGSt3nQxj7Q5_xTcNMm6NSSnw5c0C9oIENTA_8XzidnTCw';
    private $ticket = 'ticket@@@f8aPCKceyc-MHalDi3xY0ESxDtsrQ_WjeLfvi2_C8kSuXxK7bKBabt4dS8doK7b5_8AN1CIq51mKi62prCj6eg';

//    private $proxy = 'socks5://127.0.0.1:19991';
    private $proxy = 'socks5://127.0.0.1:9991';

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testExample()
    {
        $openPlatform = $this->openPlatform();

//        dd($openPlatform->getAuthorizers());

//        $app = (new \Modules\Socialite\Platforms\WechatPublic())->officialAccount;
        $app = $this->officialAccount();

//        dd($app->data_cube->interfaceSummary('2018-12-25', '2018-12-25'));

        dd($app->template_message->getPrivateTemplates());

//        $res = $app->template_message->send([
//            'touser' => 'o3adlxDh93peprJMrgGvwIFr4CO0',
//            'template_id' => 'dodvZkrXx91fujplu74vVRl2HPuvHCGf2tw1ciThaoM',
//            'url' => 'https://easywechat.org',
//            'data' => [
//                'first' => 'VALUE',
//                'keyword1' => 'VALUE',
//                'keyword2' => 'VALUE',
//                'remark' => 'VALUE',
//            ],
//        ]);
//        dd($res);

        $res = $app->template_message->send([
            'touser' => 'oO-ea1Xv7ZHaILIFrp-Gy0zNuGBA',
            'template_id' => '3m1yKUGFSfhk_d5Lym5PPyiSEAP_CReR2VW7GPliaYI',
            'url' => 'https://easywechat.org',
            'data' => [
                'first' => 'VALUE',
                'keyword1' => 'VALUE',
                'keyword2' => 'VALUE',
                'keyword3' => 'VALUE',
                'keyword4' => 'VALUE',
                'remark' => 'VALUE',
            ],
        ]);
        dd($res);

        $this->assertTrue(true);
    }

    /**
     * @return \EasyWeChat\OpenPlatform\Application
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function openPlatform()
    {
        return $this->thirdParty()->openPlatform;
    }

    /**
     * @return WechatThirdParty
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function thirdParty()
    {
        $thirdParty = new \Modules\Socialite\Platforms\WechatThirdParty([
            'http' => [
                'proxy' => $this->proxy,
            ],
        ]);

        $thirdParty->setTicket($this->ticket);

        return $thirdParty;
    }

    /**
     * @return \EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function officialAccount()
    {
        return $this->thirdParty()->officialAccount()->officialAccount;
    }

    public function getTicket()
    {
        return (new \Modules\Socialite\Platforms\WechatThirdParty())->openPlatform->verify_ticket->getTicket();
    }
}
