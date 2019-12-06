<?php

namespace App\Modules\Message\Tests;

use Tests\TestCase;
use Modules\Wechat\Entities\TemplateMessage;

class TemplateMessageTest extends TestCase
{
    public $wechat;
    public $app;

    public function testBasicTest()
    {
        $content = [
            'title' => 'Order Message',
            'keyword1' => 'Apple Watch 4',
            'keyword2' => '3299',
            'keyword3' => '1',
            'remark' => 'Hoping to see you next time !',
        ];
        $template = new TemplateMessage();
        $template->send(
            'oDiyA5gcnkWnWr6R10jMIper4PoY',
            $content,
            'base_template',
            'https://www.baidu.com'
        );
        dd('success');
    }
}
