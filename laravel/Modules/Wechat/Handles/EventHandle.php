<?php

namespace Modules\Wechat\Handles;

use Modules\Wechat\Events\ScanEvent;

class EventHandle
{
    public function subscribe($message, $app)
    {
        $ticker = $message['Ticket']; 
        $open_id = $message['FromUserName'];
        $user = $app->user->get($open_id);
        event(new ScanEvent($open_id, $user, $ticker, __FUNCTION__));

        return '欢迎关注~';
    }

    public function unsubscribe($message, $app)
    {
        $open_id = $message['FromUserName'];
        $user = $app->user->get($open_id);
        event(new ScanEvent($open_id, $user, '', __FUNCTION__));
    }

    public function scan($message, $app)
    {
        return $this->subscribe($message, $app);
    }
}
