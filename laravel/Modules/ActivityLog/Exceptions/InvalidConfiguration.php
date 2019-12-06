<?php

namespace Modules\ActivityLog\Exceptions;

use Exception;
use Modules\ActivityLog\Entities\ActivityLog;

class InvalidConfiguration extends Exception
{
    public static function modelIsNotValid(string $className)
    {
        return new static("The given model class `$className` does not extend `".ActivityLog::class.'`');
    }
}
