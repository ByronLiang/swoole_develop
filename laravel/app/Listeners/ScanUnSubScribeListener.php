<?php

namespace App\Listeners;

use Modules\Wechat\Events\ScanEvent;
use Modules\Wechat\Traits\WechatScanSocialite;

class ScanUnSubScribeListener
{
    use WechatScanSocialite;

    public function handle(ScanEvent $event)
    {
        if ($event->getType() == 'unsubscribe') {
            $this->unsubscribeSocialite($event->open_id);
        }
    }
}
