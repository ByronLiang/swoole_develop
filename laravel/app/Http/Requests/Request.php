<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected $exception = ['captcha'];

    public function authorize()
    {
        return true;
    }

    public function extractInputFromRules()
    {
        if (!method_exists($this, 'rules')) {
            return $this->all();
        }
        $rules = collect($this->rules())->keys()
            ->filter(function ($i) {
                return !collect($this->exception)->first(function ($ii) use ($i) {
                    return strstr($i, $ii);
                });
            })
            ->map(function ($rule) {
                return \Illuminate\Support\Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
            })->unique()->toArray();

        return $this->only($rules);
    }
}
