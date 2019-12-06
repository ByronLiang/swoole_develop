<?php

namespace Modules\Wechat\Entities;

use Config;
use EasyWeChat\Factory;

class Wechat
{
    private $app;

    public function __construct()
    {
        $this->app = Factory::officialAccount(Config('wechat'));
    }

    public function getApplication()
    {
        return $this->app;
    }

    public static function officialAccountHook()
    {
        \Route::any('wechat', '\Modules\Wechat\Http\Controllers\WechatController@index')
            ->name('WechatHook');
    }

    public static function officialAccountQrCode()
    {
        \Route::get('wechat_qr', '\Modules\Wechat\Http\Controllers\WechatController@showQr')
            ->name('WechatQr');
    }
}
