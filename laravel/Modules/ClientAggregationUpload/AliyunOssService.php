<?php

namespace Modules\ClientAggregationUpload;

class AliyunOssService implements FactoryInterface
{
    private $accessKeyId;
    private $accessKeySecret;
    private $endpoint;

    public function __construct()
    {
        $config = config('client_aggregation_upload.oss');
        if (!$config || !is_array($config)) {
            abort(403, '缺少oss配置');
        }
        $this->accessKeyId = $config['access_id'];
        $this->accessKeySecret = $config['access_key'];
        $this->endpoint = $config['domain'];
    }

    public function getToken()
    {
        $key = $this->accessKeySecret;

        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $expire = $now + $expire;
        $expiration = $this->gmtIso8601($expire);

        $dir = '';

        //最大文件大小.用户可以自己设置
        $conditions[] = [
            'content-length-range',
            0,
            1048576000,
        ];

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $conditions[] = [
            'starts-with',
            '$key',
            $dir,
        ];

        $arr = [
            'expiration' => $expiration,
            'conditions' => $conditions,
        ];

        $policy = json_encode($arr);
        $policy = base64_encode($policy);
        $signature = base64_encode(hash_hmac('sha1', $policy, $key, true));

        return compact('policy', 'signature', 'expire', 'dir');
    }

    public function getForm(): array
    {
        list($policy, $signature) = array_values($this->getToken());

        $OSSAccessKeyId = $this->accessKeyId;
        $success_action_status = 200;

        return compact('policy', 'OSSAccessKeyId', 'success_action_status', 'signature');
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getAccessUrl(): String
    {
        $url = $this->endpoint;

        if (!parse_url($url, PHP_URL_SCHEME)) {
        }

        return $url;
    }

    public function getUploadUrl(): String
    {
        return $this->endpoint;
    }

    public function getFileField(): String
    {
        return 'file';
    }

    private function gmtIso8601($time)
    {
        $dtStr = date('c', $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration.'Z';
    }
}
