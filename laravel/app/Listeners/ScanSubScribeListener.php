<?php

namespace App\Listeners;

use App\Events\WechatScanLogin;
use Modules\Wechat\Events\ScanEvent;
use Modules\Wechat\Traits\WechatScanSocialite;

class ScanSubScribeListener
{
    use WechatScanSocialite;

    public function handle(ScanEvent $event)
    {
        if ('subscribe' == $event->getType() || 'scan' == $event->getType()) {
            $user = $this->subscribeSocialite(
                $event->user_info,
                $event->open_id
            );
            if ($user) {
                $user = $user->showAndUpdateApiToken();
                broadcast(new WechatScanLogin(
                    $event->ticker,
                    $user,
                    $user->api_token)
                )->toOthers();
            }
        }
    }
}
