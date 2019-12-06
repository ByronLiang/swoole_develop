<?php

namespace Modules\ClientAggregationUpload;

class LocalService implements FactoryInterface
{
    private $cache_key;

    public function __construct()
    {
        $this->cache_key = self::class.md5('token_'.request()->getClientIp());
    }

    public function getForm(): array
    {
        $_token = \Cache::remember($this->cache_key, 2, function () {
            return csrf_token() ?: str_random(64);
        });

        return compact('_token');
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function getAccessUrl(): String
    {
        return url('/upload/');
    }

    public function getUploadUrl(): String
    {
        return request()->url();
    }

    public function getFileField(): String
    {
        return 'file';
    }

    public function securityCheck(\Illuminate\Http\Request $request)
    {
        $token = $request->_token;

        if (!$token || \Cache::get($this->cache_key) !== $token) {
            abort(403, 'token 无效');
        }
    }
}
