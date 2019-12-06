<?php

return [
    /*
     * local | qiniu | oss | cos
     */
    'default' => env('CLIENT_AGGREGATION_UPLOAD_DRIVE', 'local'),

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
    ],

    // https://github.com/overtrue/laravel-filesystem-qiniu
    'qiniu' => [
        'access_key' => env('QINIU_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),
        'secret_key' => env('QINIU_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),
        'bucket' => env('QINIU_BUCKET', 'test'),
        'domain' => env('QINIU_DOMAIN', 'xxx.clouddn.com'), // or host: https://xxxx.clouddn.com
        'upload_url' => env('QINIU_UPLOAD_URL', 'http://up.qiniu.com'),
    ],

    /*
     |--------------------------------------------------------------------------
     | config oss
     |--------------------------------------------------------------------------
     |
     | 若无阿里云对象存储服务，请先注册登录，创建自己的存储空间（Bucket），在Bucket中找到自己的访问秘钥
     | AccessKeyId 和AccessKeySecret。damain即Endpoint 表示 OSS 对外服务的访问域名。
     |
     |
     */

    // https://github.com/jacobcyl/Aliyun-oss-storage
    'oss' => [
        'access_id' => env('OSS_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),
        'access_key' => env('OSS_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),
        'domain' => env('OSS_DOMAIN', 'oss-cn-hangzhou.aliyuncs.com'), // OSS 外网节点或自定义外部域名
    ],

    /*
     |--------------------------------------------------------------------------
     | config cos
     |--------------------------------------------------------------------------
     |
     | 若无腾讯云对象存储服务，请先注册登录，创建自己的存储空间（Bucket），在控制台中的访问管理目录下
     | 的API密钥管理中找到自己的APPID 和 访问秘钥(无秘钥请新建秘钥) AccessKeyId 和AccessKeySecret。
     | region表示 OSS 对外服务的访问域名。Bucket配置存储空间的名字。
     |
     */

    // https://github.com/freyo/flysystem-qcloud-cos-v5
    'cos' => [
        'region' => env('COSV5_REGION', 'ap-guangzhou'),
        'credentials' => [
            'appId' => env('COSV5_APP_ID'),
            'secretId' => env('COSV5_SECRET_ID'),
            'secretKey' => env('COSV5_SECRET_KEY'),
        ],
        'bucket' => env('COSV5_BUCKET'),
    ],
];
