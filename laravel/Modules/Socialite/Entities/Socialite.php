<?php

namespace Modules\Socialite\Entities;

use Modules\Socialite\SocialiteInterface;

/**
 * @property $provider
 * @property $able
 *
 * @method Socialite whereWechatId(string $open_id, string $union_id = null)
 */
class Socialite extends \App\Models\Model
{
    protected $casts = [
        'extend' => 'object',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            if (!in_array($model->provider, array_keys(trans('socialite::Socialite.provider')))) {
                abort(500, '未定义登录类型语言: '.$model->provider);
            }
        });
    }

    public function getProviderTransAttribute()
    {
        return trans('Socialite::socialite.provider.'.$this->provider);
    }

    public function setExtendAttribute($value)
    {
        $this->attributes['extend'] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function able()
    {
        return $this->morphTo();
    }

    /**
     * @param SocialiteInterface $able
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setAble(SocialiteInterface $able)
    {
        if ($able->socialite()->where('provider', $this->provider)->first()) {
            throw new \Exception('duplicate provider，存在相同的第三方登录平台');
        }
        $this->able_id = $able->getKey();
        $this->able_type = get_class($able);
        $this->save();

        return $this;
    }

    public static function whereUniqueId($value)
    {
        return self::where('unique_id', $value);
    }
}
