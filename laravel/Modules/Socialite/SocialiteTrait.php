<?php

namespace Modules\Socialite;

use Modules\Socialite\Entities\Socialite;

/**
 * @mixin \Eloquent
 */
trait SocialiteTrait
{
    public static function bootSocialiteTrait()
    {
        static::deleting(function (self $model) {
            if (null === $model->forceDeleting || true === $model->forceDeleting) {
                $model->socialites()->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|Socialite
     */
    public function socialites(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Socialite::class, 'able');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Socialite
     */
    public function socialite(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Socialite::class, 'able');
    }

    /**
     * @param $provider
     *
     * @return mixed
     */
    public function getSocialiteOpenId($provider)
    {
        return $this->socialite()->where('provider', $provider)->value('unique_id');
    }
}
