<?php

// 配置看 https://github.com/overtrue/easy-sms
return [
    'disabled' => env('SMS_DISABLED'),

    // HTTP 请求的超时时间（秒）
    'timeout' => 1.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => storage_path('logs/sms-'.date('Y-m-d').'.log'),
        ],
        'yunpian' => [
            'api_key' => '',
        ],
        'aliyun' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'sign_name' => '',
        ],
        'alidayu' => [
            'app_key' => '',
            'app_secret' => '',
            'sign_name' => '',
        ],
        'submail' => [
            'app_id' => '',
            'app_key' => '',
            'project' => '',
        ],
        'luosimao' => [
            'api_key' => '',
        ],
        'yuntongxun' => [
            'app_id' => '',
            'account_sid' => '',
            'account_token' => '',
            'is_sub_account' => false,
        ],
        'huyi' => [
            'api_id' => '',
            'api_key' => '',
        ],
        'juhe' => [
            'app_key' => '',
        ],
        'sendcloud' => [
            'sms_user' => '',
            'sms_key' => '',
            'timestamp' => false, // 是否启用时间戳
        ],
        'baidu' => [
            'ak' => '',
            'sk' => '',
            'invoke_id' => '',
            'domain' => '',
        ],
        'huaxin' => [
            'user_id' => '',
            'password' => '',
            'account' => '',
            'ip' => '',
            'ext_no' => '',
        ],
        'chuanglan' => [
            'username' => '',
            'password' => '',
        ],
        'rongcloud' => [
            'app_key' => '',
            'app_secret' => '',
        ],
        'tianyiwuxian' => [
            'username' => '', //用户名
            'password' => '', //密码
            'gwid' => '', //网关ID
        ],
        'twilio' => [
            'account_sid' => '', // sid
            'from' => '', // 发送的号码 可以在控制台购买
            'token' => '', // apitoken
        ],
        'qcloud' => [
            'sdk_app_id' => '', // SDK APP ID
            'app_key' => '', // APP KEY
        ],
    ],
    'template_id' => [
        'captcha' => '',
    ],
];
