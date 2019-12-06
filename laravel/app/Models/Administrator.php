<?php

namespace App\Models;

use App\Models\Traits\SafetyPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Traits\JwtTrait;

class Administrator extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, SafetyPassword;
    use JwtTrait;
}
