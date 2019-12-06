<?php

return [
    'notify_action' => '',
    'alipay_default' => 'default',
    'wechat_default' => 'app',
    'alipay' => [
        'default' => [
            'app_id' => env('ALIPAY_APPID'),
            'alipay_public_key_path' => storage_path('certificate/alipay_default/alipay_public_key.pem'),
            'app_private_key_path' => storage_path('certificate/alipay_default/app_private_key.pem'),
            'rsa2' => false,
            'sandbox' => false,
        ],
        'test' => [
            'app_id' => '2016072800110686',
            'alipay_public_key_path' => storage_path('certificate/alipay_test_rsa2/alipay_public_key.pem'),
            'app_private_key_path' => storage_path('certificate/alipay_test_rsa2/app_private_key.pem'),
            'rsa2' => true,
            'sandbox' => true,
        ],
        'app' => [
            'app_id' => env('ALIPAY_APPID'),
            'alipay_public_key_path' => storage_path('certificate/alipay_default/alipay_public_key.pem'),
            'app_private_key_path' => storage_path('certificate/alipay_default/app_private_key.pem'),
            'rsa2' => false,
            'sandbox' => false,
            'gateway' => 'Alipay_AopApp',
            'product_code' => 'QUICK_MSECURITY_PAY',
        ],
        'web' => [
            'app_id' => env('ALIPAY_APPID'),
            'alipay_public_key_path' => storage_path('certificate/alipay_default/alipay_public_key.pem'),
            'app_private_key_path' => storage_path('certificate/alipay_default/app_private_key.pem'),
            'rsa2' => false,
            'sandbox' => false,
            'gateway' => 'Alipay_AopPage',
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ],
    ],
    'wechat' => [
        'app' => [
            'app_id' => env('WECHAT_OPEN_APPID'),
            'secret' => env('WECHAT_OPEN_SECRET'),
            //支付相关
            'mch_id' => env('WECHAT_PAYMENT_MCH_ID'),
            'key' => env('WECHAT_PAYMENT_KEY'),
            'cert_path' => storage_path('certificate/wechat_default/apiclient_cert.pem'),
            'key_path' => storage_path('certificate/wechat_default/apiclient_key.pem'),
        ],
        'applet' => [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID'),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET'),
            //支付相关
            'mch_id' => env('WECHAT_PAYMENT_MCH_ID'),
            'key' => env('WECHAT_PAYMENT_KEY'),
            'cert_path' => storage_path('certificate/wechat_default/apiclient_cert.pem'),
            'key_path' => storage_path('certificate/wechat_default/apiclient_key.pem'),
        ],
    ],
];
