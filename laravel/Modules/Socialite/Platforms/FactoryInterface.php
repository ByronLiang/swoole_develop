<?php

namespace Modules\Socialite\Platforms;

interface FactoryInterface
{
    /**
     * @param string $return 授权成功返回地址
     *
     * @return mixed
     */
    public function handle();

    /**
     * 获取模型.
     *
     * @param string $provider
     *
     * @return \Modules\Socialite\Entities\Socialite
     */
    public function socialite(string $provider): \Modules\Socialite\Entities\Socialite;
}
