<?php

namespace Modules\ClientAggregationUpload;

use stdClass;

/**
 * reference: https://github.com/tpyo/amazon-s3-php-class
 * Class AwsS3Service.
 */
class AwsS3Service implements FactoryInterface
{
    private $accessKey;
    private $secretKey;
    private $bucket;
    private $region;

    public function __construct()
    {
        $this->accessKey = config('client_aggregation_upload.key');
        $this->secretKey = config('client_aggregation_upload.secret');
        $this->bucket = config('client_aggregation_upload.bucket');
        $this->region = config('client_aggregation_upload.region');
    }

    public function getHttpUploadPostParams($bucket, $uriPrefix = '', $lifetime = 3600, $maxFileSize = 5242880)
    {
        $successRedirect = '201';
        $amzHeaders = [];
        $headers = [];
        $flashVars = false;
        $acl = 'public-read';

        // Create policy object
        $policy = new stdClass();
        $policy->expiration = gmdate('Y-m-d\TH:i:s\Z', ($this->__getTime() + $lifetime));
        $policy->conditions = [];
        $obj = new stdClass();
        $obj->bucket = $bucket;
        array_push($policy->conditions, $obj);
        $obj = new stdClass();
        $obj->acl = $acl;
        array_push($policy->conditions, $obj);

        $obj = new stdClass(); // 200 for non-redirect uploads
        if (is_numeric($successRedirect) && in_array((int) $successRedirect, [200, 201])) {
            $obj->success_action_status = (string) $successRedirect;
        } else { // URL
            $obj->success_action_redirect = $successRedirect;
        }
        array_push($policy->conditions, $obj);

        array_push($policy->conditions, ['starts-with', '$key', $uriPrefix]);
        if ($flashVars) {
            array_push($policy->conditions, ['starts-with', '$Filename', '']);
        }
        foreach (array_keys($headers) as $headerKey) {
            array_push($policy->conditions, ['starts-with', '$'.$headerKey, '']);
        }
        foreach ($amzHeaders as $headerKey => $headerVal) {
            $obj = new stdClass();
            $obj->{$headerKey} = (string) $headerVal;
            array_push($policy->conditions, $obj);
        }
        array_push($policy->conditions, ['content-length-range', 0, $maxFileSize]);
//        dd($policy);
        $policy = base64_encode(str_replace('\/', '/', json_encode($policy)));

        // Create parameters
        $params = new stdClass();
        $params->AWSAccessKeyId = $this->accessKey;
        $params->acl = $acl;
        $params->policy = $policy;
        unset($policy);
        $params->signature = $this->__getHash($params->policy);
        if (is_numeric($successRedirect) && in_array((int) $successRedirect, [200, 201])) {
            $params->success_action_status = (string) $successRedirect;
        } else {
            $params->success_action_redirect = $successRedirect;
        }
        foreach ($headers as $headerKey => $headerVal) {
            $params->{$headerKey} = (string) $headerVal;
        }
        foreach ($amzHeaders as $headerKey => $headerVal) {
            $params->{$headerKey} = (string) $headerVal;
        }

        return $params;
    }

    private function __getHash($string)
    {
        return base64_encode(extension_loaded('hash') ?
            hash_hmac('sha1', $string, $this->secretKey, true) : pack('H*', sha1(
                (str_pad($this->secretKey, 64, chr(0x00)) ^ (str_repeat(chr(0x5c), 64))).
                pack('H*', sha1((str_pad($this->secretKey, 64, chr(0x00)) ^
                        (str_repeat(chr(0x36), 64))).$string)))));
    }

    public function __getTime()
    {
        $__timeOffset = 0;

        return time() + $__timeOffset;
    }

    public function getForm(): array
    {
        return (array) $this->getHttpUploadPostParams($this->bucket);
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getAccessUrl(): String
    {
        return env('AWS_CDN_URL') ?: $this->getUploadUrl();
    }

    public function getUploadUrl(): String
    {
        return "https://$this->bucket.s3.$this->region.amazonaws.com";
    }

    public function getFileField(): String
    {
        return 'key';
    }
}
