<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @mixin \Eloquent
 */
class EloquentStorage extends Eloquent implements SettingStorageContract
{
    protected $fillable = ['key', 'value', 'locale'];

    protected $table = 'settings';

    /**
     * @param string      $key
     * @param string|null $lang
     *
     * @return Eloquent|EloquentStorage|object|string|null
     */
    public function retrieve($key, string $lang = null)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        return $setting->first();
    }

    /**
     * @param string      $key
     * @param mixed       $value
     * @param string|null $lang
     *
     * @return mixed|void
     */
    public function store($key, $value, string $lang = null)
    {
        $setting = ['key' => $key, 'value' => $value];

        if (!is_null($lang)) {
            $setting['locale'] = $lang;
        }

        static::create($setting);
    }

    /**
     * @param string $key
     * @param $value
     * @param string|null $lang
     *
     * @return bool|void
     */
    public function modify($key, $value, string $lang = null)
    {
        if (!is_null($lang)) {
            $setting = static::where('locale', $lang);
        } else {
            $setting = new static();
        }

        $setting->where('key', $key)->update(['value' => $value]);
    }

    /**
     * @param string      $key
     * @param string|null $lang
     *
     * @return mixed|void
     *
     * @throws \Exception
     */
    public function forget($key, string $lang = null)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        $setting->delete();
    }
}
