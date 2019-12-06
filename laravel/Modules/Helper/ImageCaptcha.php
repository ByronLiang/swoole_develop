<?php

namespace Modules\Helper;

class ImageCaptcha
{
    public function getCacheKey()
    {
        return md5(__METHOD__.request()->getClientIp().request()->userAgent());
    }

    public function store()
    {
        \Cache::put($this->getCacheKey(), time(), 60);
    }

    public function has()
    {
        return \Cache::has($this->getCacheKey());
    }

    public function remove()
    {
        \Cache::forget($this->getCacheKey());
    }

    public function url()
    {
        $captcha_url = url('/captcha/default');

        return $this->has() ? compact('captcha_url') : null;
    }
}
