<?php

namespace App\Models;

// use App\Models\Traits\ISO8601TimeFormat;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use \Modules\Helper\ModelExpansion\BelongsToOneTrait;
    use \Modules\Helper\ModelExpansion\HasInTrait;
    use \Modules\Helper\ModelExpansion\LoadCountTrait;

    protected $guarded = ['id'];

    // protected $dateFormat = 'U';

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];
}
