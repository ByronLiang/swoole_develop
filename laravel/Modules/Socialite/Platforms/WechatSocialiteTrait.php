<?php

namespace Modules\Socialite\Platforms;

use Modules\Socialite\Entities\Socialite;

trait WechatSocialiteTrait
{
    /**
     * 微信专属，获取多平台关联的ID.
     *
     * @return |null
     */
    public function getUnionId()
    {
        if ($this->user instanceof \Overtrue\Socialite\User) {
            $original = $this->user->getOriginal();

            return $original['unionid'] ?? $original['unionId'] ?? '';
        }

        if (is_array($this->user)) {
            return $this->user['unionid'] ?? $this->user['unionId'] ?? '';
        }

        abort(500, __METHOD__.' $this->user非法对象');
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getAttributes()
    {
        if (!$this->user instanceof \Overtrue\Socialite\User) {
            abort(500, __METHOD__.' $this->user 非法对象');
        }

        $user = $this->user;

        if (empty($user->getId())) {
            throw new \Exception('WECHAT LOGIN ERROR');
        }

        return [
            'unique_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'nickname' => $user->getNickname(),
            'extend' => $user->getOriginal(),
        ];
    }

    /**
     * 返回模型.
     *
     * @param string $provider
     *
     * @return Socialite
     *
     * @throws \Exception
     */
    public function socialite(string $provider): Socialite
    {
        $attributes = $this->getAttributes();
        $attributes['provider'] = $provider;

        $unionId = $this->getUnionId();

        if ($unionId) {
            $socialites = Socialite::where('extend', 'like', '%'.$unionId.'%')->get();

            if ($socialite = $socialites->firstWhere('unique_id', $attributes['unique_id'])) {
                $socialite->update($attributes);

                return $socialite;
            }

            $first_socialite = $socialites->first();
        } elseif ($socialite = Socialite::where('unique_id', $attributes['unique_id'])->first()) {
            $socialite->update($attributes);

            return $socialite;
        }

        $socialite = Socialite::create($attributes);
        if (isset($first_socialite) && $first_socialite->able) {
            $socialite->setAble($first_socialite->able);
        }

        return $socialite;
    }
}
