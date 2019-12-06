<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckPassword implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        if (auth()->guest()) {
            abort(401, 'Unauthorized');
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \Hash::check($value, auth()->user()->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 错误';
    }
}
