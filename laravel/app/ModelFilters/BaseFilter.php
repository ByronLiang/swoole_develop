<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

//use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin \App\Models\Model
 */
abstract class BaseFilter extends \EloquentFilter\ModelFilter
{
    final public function handle(): EloquentBuilder
    {
        $eloquentBuilder = parent::handle();

        // 默认ID倒序
        $query = $this->getQuery();
        if (!$query->orders && !$query->unionOrders && $this->getModel()->getKeyName()) {
            $query->orderBy($this->getModel()->getKeyName(), 'desc');
        }

        return $eloquentBuilder;
    }

    final public function id($val)
    {
        return $this->where(__FUNCTION__, (int) $val);
    }

    final public function ids($val)
    {
        $val = $this->stringToArray($val);
        $val = array_filter(array_map(function ($i) {
            return (int) $i;
        }, $val));

        return $this
            ->whereIn('id', $val)
            ->orderByRaw('FIND_IN_SET(`id`, \''.implode(',', $val).'\')');
    }

    final protected function stringToArray($val, $delimiter = ','): array
    {
        if (is_array($val)) {
            return $val;
        }

        return explode($delimiter, $val);
    }

    final public function with($relations)
    {
        return parent::with(is_string($relations) ? func_get_args() : $relations);
    }

    final public function withCount($relations)
    {
        return parent::withCount(is_string($relations) ? func_get_args() : $relations);
    }

    final public function sortOrder(array $val)
    {
        foreach ((array) $val as $k => $v) {
            $this->orderBy($k, $v);
        }

        return $this;
    }

    final public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', \Closure $callback = null)
    {
        return parent::has($relation, $operator, $count, $boolean, $callback);
    }

    final public function doesntHave($relation, $boolean = 'and', \Closure $callback = null)
    {
        return parent::doesntHave($relation, $boolean, $callback);
    }

    final public function trashed($val)
    {
        if ('with' == $val) {
            $this->withTrashed();
        }
        if ('without' == $val) {
            $this->withoutTrashed();
        }
        if ('only' == $val) {
            $this->onlyTrashed();
        }

        return $this;
    }
}
