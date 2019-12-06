<?php

namespace Modules\Setting\Entities;

interface SettingStorageContract
{
    /**
     * Return all data.
     *
     * @return array
     */
    public static function all();

    /**
     * Return setting value or default value by key.
     *
     * @param string $key
     * @param string $lang
     *
     * @return string
     */
    public function retrieve($key, string $lang = null);

    /**
     * Set the setting by key and value.
     *
     * @param string      $key
     * @param mixed       $value
     * @param string|null $lang
     *
     * @return mixed
     */
    public function store($key, $value, string $lang = null);

    /**
     * Check if the setting exists.
     *
     * @param string $key
     * @param $value
     * @param string|null $lang
     *
     * @return bool
     */
    public function modify($key, $value, string $lang = null);

    /**
     * Delete a setting.
     *
     * @param string      $key
     * @param string|null $lang
     *
     * @return mixed
     */
    public function forget($key, string $lang = null);
}
