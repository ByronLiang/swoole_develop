<?php

namespace Modules\Wechat\Traits;

use App\Models\User;
use Modules\Socialite\Entities\Socialite;

trait WechatScanSocialite
{
    public function subscribeSocialite($info, $open_id)
    {
        $exist = Socialite::where('provider', 'wechat_scan')
            ->where('unique_id', $open_id)
            ->count();
        if ($exist < 1) {
            $socialite = Socialite::create([
                'provider' => 'wechat_scan',
                'unique_id' => $open_id,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'extend' => $info,
            ]);
            $user = User::create([
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
            ]);
            $socialite->setAble($user);
        } else {
            $socialite = Socialite::whereUniqueId($open_id)
                ->with('able')
                ->first();
            $user = $socialite->able;
        }

        return $user;
    }

    public function unsubscribeSocialite($open_id)
    {
        Socialite::where('unique_id', $open_id)->delete();
    }
}
