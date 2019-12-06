<?php

return [
    'debug' => env('WECHAT_DEBUG', true),
    'app_id' => env('WECHAT_PUBLIC_APPID'),
    'secret' => env('WECHAT_PUBLIC_SECRET'),
    'token' => env('WECHAT_PUBLIC_TOKEN'),
    'log' => [
        'level' => 'debug',
        'file' => storage_path('logs/wechat-'.date('Y-m-d').'.log'),
    ],
    'oauth' => [
        'scopes' => ['snsapi_userinfo'],
        'callback' => '',
    ],
    'http' => [
        'timeout' => 60, // 超时时间（秒）
    ],
];
