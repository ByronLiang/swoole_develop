<?php

namespace App\ModelFilters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

//use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin User
 */
class UserFilter extends BaseFilter
{
    public function keyword($val)
    {
        return $this->where(function (EloquentBuilder $q) use ($val) {
            $q->orWhere('nickname', 'like', '%'.$val.'%');
            $q->orWhere('account', 'like', '%'.$val.'%');
        });
    }

    public function rangeDate($val)
    {
        if (isset($val[1])) {
            $val[1] .= ' 23:59:59';
        }

        return $this->whereBetween('created_at', $val);
    }
}
