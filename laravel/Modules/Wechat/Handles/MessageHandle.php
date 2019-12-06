<?php

namespace Modules\Wechat\Handles;

class MessageHandle
{
    public static function handle($app)
    {
        return function ($message) use ($app) {
            \Log::info($message);
            switch ($message['MsgType']) {
                case 'event':
                    // 调用事件类的方法
                    return call_user_func_array(
                        [new EventHandle(), strtolower($message['Event'])],
                        [$message, $app]
                    );

                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
            }

            return 'I can not find it';
        };
    }

    // protected function subscribe($message, $app)
    // {
    //     \Log::info(__FUNCTION__);
    //     $open_id = $message['FromUserName'];
    //     $user = $app->user->get($open_id);
    //     \Log::info($user);

    //     return '欢迎关注~';
    // }

    // protected function unsubscribe($message, $app)
    // {
    //     \Log::info(__FUNCTION__);
    //     $open_id = $message['FromUserName'];
    //     $user = $app->user->get($open_id);
    //     \Log::info($user);
    // }
}
