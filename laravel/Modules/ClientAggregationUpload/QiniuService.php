<?php

namespace Modules\ClientAggregationUpload;

use Qiniu\Auth;

class QiniuService implements FactoryInterface
{
    private $accessKey;
    private $secretKey;
    private $bucket;
    private $access_url;
    private $upload_url;

    public function __construct()
    {
        $qiniu = config('client_aggregation_upload.qiniu');
        if (!$qiniu) {
            abort(403, '缺少七牛配置');
        }
        $this->accessKey = $qiniu['access_key'];
        $this->secretKey = $qiniu['secret_key'];
        $this->bucket = $qiniu['bucket'];
        $this->access_url = $qiniu['domain'];
        $this->upload_url = $qiniu['upload_url'];
    }

    public function getToken()
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucket, null, 60 * 60);

        return $token;
    }

    public function getForm(): array
    {
        $token = $this->getToken();

        return compact('token');
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getAccessUrl(): String
    {
        $url = $this->access_url;

        if (!parse_url($url, PHP_URL_SCHEME)) {
        }

        return $url;
    }

    public function getUploadUrl(): String
    {
        return $this->upload_url;
    }

    public function getFileField(): String
    {
        return 'file';
    }
}
