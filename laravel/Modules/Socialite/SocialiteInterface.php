<?php

namespace Modules\Socialite;

interface SocialiteInterface
{
    public static function bootSocialiteTrait();

    public function socialites(): \Illuminate\Database\Eloquent\Relations\MorphMany;

    public function socialite(): \Illuminate\Database\Eloquent\Relations\MorphOne;

    public function getSocialiteOpenId($provider);

    public function getKey();
}
