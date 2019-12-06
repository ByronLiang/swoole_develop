<?php

namespace Modules\Sms;

class Sms extends \Overtrue\EasySms\EasySms
{
    public $disabled;

    public function __construct()
    {
        parent::__construct(config('sms'));
        $this->disabled = config('sms.disabled');
    }
}
