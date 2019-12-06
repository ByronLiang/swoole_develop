<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        // https://github.com/overtrue/laravel-filesystem-qiniu
        'qiniu' => [
            'driver' => 'qiniu',
            'access_key' => env('QINIU_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),
            'secret_key' => env('QINIU_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),
            'bucket' => env('QINIU_BUCKET', 'test'),
            'domain' => env('QINIU_DOMAIN', 'xxx.clouddn.com'), // or host: https://xxxx.clouddn.com
            'upload_url' => env('QINIU_UPLOAD_URL', 'http://up.qiniu.com'),
        ],

        // https://github.com/jacobcyl/Aliyun-oss-storage
        'oss' => [
            'driver' => 'oss',
            'access_id' => env('OSS_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),
            'access_key' => env('OSS_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),
            'bucket' => env('OSS_BUCKET', 'xxxxxxxxxxxxxxxx'),
            'endpoint' => env('OSS_DOMAIN', 'oss-cn-hangzhou.aliyuncs.com'), // OSS 外网节点或自定义外部域名
            //'endpoint_internal' => '<internal endpoint [OSS内网节点] 如：oss-cn-shenzhen-internal.aliyuncs.com>', // v2.0.4 新增配置属性，如果为空，则默认使用 endpoint 配置(由于内网上传有点小问题未解决，请大家暂时不要使用内网节点上传，正在与阿里技术沟通中)
            'cdnDomain' => env('OSS_CDN_DOMAIN', 'xxxxxxxxxxxxxxxx'), // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
            'ssl' => false, // true to use 'https://' and false to use 'http://'. default is false,
            'isCName' => false, // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
            'debug' => false,
        ],

        // https://github.com/freyo/flysystem-qcloud-cos-v5
        'cosv5' => [
            'driver' => 'cosv5',
            'region' => env('COSV5_REGION', 'ap-guangzhou'),
            'credentials' => [
                'appId' => env('COSV5_APP_ID'),
                'secretId' => env('COSV5_SECRET_ID'),
                'secretKey' => env('COSV5_SECRET_KEY'),
            ],
            'timeout' => env('COSV5_TIMEOUT', 60),
            'connect_timeout' => env('COSV5_CONNECT_TIMEOUT', 60),
            'bucket' => env('COSV5_BUCKET'),
            'cdn' => env('COSV5_CDN'),
            'scheme' => env('COSV5_SCHEME', 'https'),
        ],
    ],
];
