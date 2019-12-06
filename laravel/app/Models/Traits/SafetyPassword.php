<?php

namespace App\Models\Traits;

trait SafetyPassword
{
    /**
     * 设置密码加密方式.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
