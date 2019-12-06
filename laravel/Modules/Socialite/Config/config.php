<?php

return [
    'wechat' => [
        'third_party' => [
            'app_id' => env('WECHAT_THIRD_PARTY_APPID'),
            'secret' => env('WECHAT_THIRD_PARTY_SECRET'),
            'token' => env('WECHAT_THIRD_PARTY_TOKEN'),
            'aes_key' => env('WECHAT_THIRD_PARTY_AESKEY'),
            'sub' => [
                'public' => [
                    'default' => env('WECHAT_PUBLIC_APPID'),
                ],
                'applet' => [
                    'default' => env('WECHAT_APPLET_APPID'),
                ],
            ],
        ],
        'public' => [
            'app_id' => env('WECHAT_PUBLIC_APPID'),
            'secret' => env('WECHAT_PUBLIC_SECRET'),
        ],
        'applet' => [
            'app_id' => env('WECHAT_PUBLIC_APPID'),
            'secret' => env('WECHAT_PUBLIC_SECRET'),
        ],
        'qrcode' => [
            'app_id' => env('WECHAT_QRCODE_APPID'),
            'secret' => env('WECHAT_QRCODE_SECRET'),
        ],
        'app' => [
            'app_id' => env('WECHAT_APP_APPID'),
            'secret' => env('WECHAT_APP_SECRET'),
        ],
    ],
];
