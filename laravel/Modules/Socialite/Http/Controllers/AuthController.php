<?php

namespace Modules\Socialite\Http\Controllers;

use App\Models\User;

final class AuthController
{
    /**
     * @OA\Get(path="/auth/oauth/wechat_public",tags={"认证"},summary="微信授权登陆|重定向到此链接即可",description="",
     *     @OA\Parameter(name="return",in="query",description="授权成功后返回的链接",@OA\Schema(type="string")),
     *     @OA\Response(response=200,description="successful operation",@OA\MediaType(mediaType="application/json")),
     * )
     */
    public function getOauth($provider)
    {
        $platform = new \Modules\Socialite\Platforms\Factory($provider);

        $res = $platform->getRequestHandle('/')->handle();

        $return = request('return');

        if ($res instanceof \Modules\Socialite\Entities\Socialite) {
            $socialite = $res;
            $user = $socialite->able;

            if (!$user) {
                $user = User::create([
                    'nickname' => $socialite->nickname,
                    'avatar' => $socialite->avatar,
                    'api_token' => true,
                ]);

                $socialite->setAble($user);
            }
        } elseif (false === $res) {
            return redirect($return);
        } else {
            return $res;
        }

        $return .= false === strpos($return, '?') ? '?' : '&';
        $return .= 'api_token='.$user->api_token;

        return redirect($return);
    }

    public function postOauth($provider)
    {
    }
}
