<?php

namespace Modules\ClientAggregationUpload;

use GuzzleHttp\Client;

class TencentCosService implements FactoryInterface
{
    private $appId;
    private $secretId;
    private $secretKey;

    private $region;
    private $bucket;

    private $config;

    public function __construct()
    {
        $config = config('client_aggregation_upload.cos');
        $this->appId = array_get($config, 'credentials.appId');
        $this->secretId = array_get($config, 'credentials.secretId');
        $this->secretKey = array_get($config, 'credentials.secretKey');
        $this->region = array_get($config, 'region');
        $this->bucket = array_get($config, 'bucket');

        $this->config = [
            'Url' => 'https://sts.api.qcloud.com/v2/index.php',
            'Domain' => 'sts.api.qcloud.com',
            'Proxy' => '',
            'SecretId' => $this->secretId, // 固定密钥
            'SecretKey' => $this->secretKey, // 固定密钥
            'Bucket' => "{$this->bucket}-{$this->appId}",
            'Region' => $this->region,
            'AllowPrefix' => '*',
        ];
    }

    // obj 转 query string
    public function json2str($obj)
    {
        ksort($obj);
        $arr = [];
        foreach ($obj as $key => $val) {
            array_push($arr, $key.'='.$val);
        }

        return join('&', $arr);
    }

    // 计算临时密钥用的签名
    public function getSignature($opt, $key, $method)
    {
        $config = $this->config;
        $formatString = $method.$config['Domain'].'/v2/index.php?'.$this->json2str($opt);
        $sign = hash_hmac('sha1', $formatString, $key);
        $sign = base64_encode(hex2bin($sign));

        return $sign;
    }

    // 获取临时密钥
    public function getTempKeys()
    {
        $config = $this->config;

        // 判断是否修改了 AllowPrefix
        if ('_ALLOW_DIR_/*' === $config['AllowPrefix']) {
            return ['error' => '请修改 AllowPrefix 配置项，指定允许上传的路径前缀'];
        }

        $ShortBucketName = substr($config['Bucket'], 0, strripos($config['Bucket'], '-'));
        $AppId = substr($config['Bucket'], 1 + strripos($config['Bucket'], '-'));
        $policy = [
            'version' => '2.0',
            'statement' => [
                [
                    'action' => [
                        // // 这里可以从临时密钥的权限上控制前端允许的操作
                        // 'name/cos:*', // 这样写可以包含下面所有权限

                        // // 列出所有允许的操作
                        // // ACL 读写
                        // 'name/cos:GetBucketACL',
                        // 'name/cos:PutBucketACL',
                        // 'name/cos:GetObjectACL',
                        // 'name/cos:PutObjectACL',
                        // // 简单 Bucket 操作
                        // 'name/cos:PutBucket',
                        // 'name/cos:HeadBucket',
                        // 'name/cos:GetBucket',
                        // 'name/cos:DeleteBucket',
                        // 'name/cos:GetBucketLocation',
                        // // Versioning
                        // 'name/cos:PutBucketVersioning',
                        // 'name/cos:GetBucketVersioning',
                        // // CORS
                        // 'name/cos:PutBucketCORS',
                        // 'name/cos:GetBucketCORS',
                        // 'name/cos:DeleteBucketCORS',
                        // // Lifecycle
                        // 'name/cos:PutBucketLifecycle',
                        // 'name/cos:GetBucketLifecycle',
                        // 'name/cos:DeleteBucketLifecycle',
                        // // Replication
                        // 'name/cos:PutBucketReplication',
                        // 'name/cos:GetBucketReplication',
                        // 'name/cos:DeleteBucketReplication',
                        // // 删除文件
                        // 'name/cos:DeleteMultipleObject',
                        // 'name/cos:DeleteObject',
                        // 简单文件操作
                        'name/cos:PutObject',
                        'name/cos:PostObject',
                        'name/cos:AppendObject',
                        'name/cos:GetObject',
                        'name/cos:HeadObject',
                        'name/cos:OptionsObject',
                        'name/cos:PutObjectCopy',
                        'name/cos:PostObjectRestore',
                        // 分片上传操作
                        'name/cos:InitiateMultipartUpload',
                        'name/cos:ListMultipartUploads',
                        'name/cos:ListParts',
                        'name/cos:UploadPart',
                        'name/cos:CompleteMultipartUpload',
                        'name/cos:AbortMultipartUpload',
                    ],
                    'effect' => 'allow',
                    'principal' => ['qcs' => ['*']],
                    'resource' => [
                        'qcs::cos:'.$config['Region'].':uid/'.$AppId.':prefix//'.$AppId.'/'.$ShortBucketName.'/',
                        'qcs::cos:'.$config['Region'].':uid/'.$AppId.':prefix//'.$AppId.'/'.$ShortBucketName.'/'.$config['AllowPrefix'],
                    ],
                ],
            ],
        ];

        $policyStr = str_replace('\\/', '/', json_encode($policy));

        return \Cache::remember(self::class.$policyStr, 1, function () use ($config, $policyStr) {
            $Action = 'GetFederationToken';
            $Nonce = rand(10000, 20000);
            $Timestamp = time() - 1;
            $Method = 'GET';

            $params = [
                'Action' => $Action,
                'Nonce' => $Nonce,
                'Region' => '',
                'SecretId' => $config['SecretId'],
                'Timestamp' => $Timestamp,
                'durationSeconds' => 7200,
                'name' => '',
                'policy' => $policyStr,
            ];
            $params['Signature'] = urlencode($this->getSignature($params, $config['SecretKey'], $Method));

            $url = $config['Url'].'?'.$this->json2str($params);

            $result = (new Client())
                ->get($url)
                ->getBody()
                ->getContents();

            $result = json_decode($result, true);
            if (isset($result['data'])) {
                $result = $result['data'];
            }

            return $result;
        });
    }

    // 计算 COS API 请求用的签名
    public function getAuthorization($keys, $method, $pathname)
    {
        // 获取个人 API 密钥 https://console.qcloud.com/capi
        $SecretId = $keys['credentials']['tmpSecretId'];
        $SecretKey = $keys['credentials']['tmpSecretKey'];

        // 整理参数
        $query = [];
        $headers = [];
        $method = strtolower($method ? $method : 'get');
        $pathname = $pathname ? $pathname : '/';
        '/' != substr($pathname, 0, 1) && ($pathname = '/'.$pathname);

        // 工具方法
        function getObjectKeys($obj)
        {
            $list = array_keys($obj);
            sort($list);

            return $list;
        }

        function obj2str($obj)
        {
            $list = [];
            $keyList = getObjectKeys($obj);
            $len = count($keyList);
            for ($i = 0; $i < $len; ++$i) {
                $key = $keyList[$i];
                $val = isset($obj[$key]) ? $obj[$key] : '';
                $key = strtolower($key);
                $list[] = rawurlencode($key).'='.rawurlencode($val);
            }

            return implode('&', $list);
        }

        // 签名有效起止时间
        $now = time() - 1;
        $expired = $now + 600; // 签名过期时刻，600 秒后

        // 要用到的 Authorization 参数列表
        $qSignAlgorithm = 'sha1';
        $qAk = $SecretId;
        $qSignTime = $now.';'.$expired;
        $qKeyTime = $now.';'.$expired;
        $qHeaderList = strtolower(implode(';', getObjectKeys($headers)));
        $qUrlParamList = strtolower(implode(';', getObjectKeys($query)));

        // 签名算法说明文档：https://www.qcloud.com/document/product/436/7778
        // 步骤一：计算 SignKey
        $signKey = hash_hmac('sha1', $qKeyTime, $SecretKey);

        // 步骤二：构成 FormatString
        $formatString = implode("\n", [strtolower($method), $pathname, obj2str($query), obj2str($headers), '']);

        // 步骤三：计算 StringToSign
        $stringToSign = implode("\n", ['sha1', $qSignTime, sha1($formatString), '']);

        // 步骤四：计算 Signature
        $qSignature = hash_hmac('sha1', $stringToSign, $signKey);

        // 步骤五：构造 Authorization
        $authorization = implode('&', [
            'q-sign-algorithm='.$qSignAlgorithm,
            'q-ak='.$qAk,
            'q-sign-time='.$qSignTime,
            'q-key-time='.$qKeyTime,
            'q-header-list='.$qHeaderList,
            'q-url-param-list='.$qUrlParamList,
            'q-signature='.$qSignature,
        ]);

        return $authorization;
    }

    public function getForm(): array
    {
        $result = $this->getTempKeys();

        if (isset($result['code'])) {
            abort(500, json_encode($result));
        }

        $form['Signature'] = $this->getAuthorization($result, 'POST', '/');

        $form['x-cos-security-token'] = array_get($result, 'credentials.sessionToken');

        return $form;
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getAccessUrl(): String
    {
        return "http://{$this->bucket}-{$this->appId}.cosgz.myqcloud.com/";
    }

    public function getUploadUrl(): String
    {
        return "https://{$this->bucket}-{$this->appId}.cos.{$this->region}.myqcloud.com";
    }

    public function getFileField(): String
    {
        return 'file';
    }
}
