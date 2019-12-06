<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Traits\SafetyPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Socialite\SocialiteInterface;
use Modules\Socialite\SocialiteTrait;
use App\Models\Traits\JwtTrait;

class User extends Model implements AuthenticatableContract, SocialiteInterface, JWTSubject
{
    use Authenticatable, SafetyPassword, SoftDeletes;
    use \EloquentFilter\Filterable;
    use SocialiteTrait;
    use JwtTrait;

    public function showAndUpdateApiToken(array $other_data = [])
    {
        foreach ($other_data as $k => $v) {
            $this->{$k} = $v;
        }
        $this->api_token = str_random(64);
        $this->makeVisible('api_token');
        $this->save();

        return $this;
    }
}
