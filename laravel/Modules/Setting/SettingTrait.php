<?php

namespace Modules\Setting;

trait SettingTrait
{
    public function gets($keys)
    {
        if (!is_array($keys)) {
            $keys = explode(',', $keys);
        }
        $keys = array_filter($keys);

        if (empty($keys)) {
            return null;
        }

        $settings = [];
        foreach ($keys as $v) {
            $settings[$v] = \Setting::get($v);
        }

        return array_filter($settings);
    }

    public function sets(array $arr)
    {
        foreach ($arr as $k => $v) {
            \Setting::set($k, $v);
        }
    }

    public function get($key)
    {
        return \Setting::get($key);
    }

    public function set($key, $value)
    {
        \Setting::set($key, $value);
    }

    public function forget($key)
    {
        \Setting::forget($key);
    }
}
