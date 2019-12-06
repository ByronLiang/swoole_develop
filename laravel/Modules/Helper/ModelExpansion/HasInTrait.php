<?php

namespace Modules\Helper\ModelExpansion;

/**
 * @method static \Eloquent hasIn(string $relation, $callback = null)
 */
trait HasInTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $relation
     * @param \Closure|null                         $callback
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Exception
     */
    public function scopeHasIn(\Illuminate\Database\Eloquent\Builder $builder, string $relation, \Closure $callback = null)
    {
        $relation = \Illuminate\Database\Eloquent\Relations\Relation::noConstraints(function () use ($relation) {
            return $this->getModel()->{$relation}();
        });

        if ($callback) {
            $relation->where($callback);
        }

        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne
            || $relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany
        ) {
            $relation->select($relation->getQualifiedForeignKeyName());

            return $builder->whereIn($relation->getQualifiedParentKeyName(), $relation->getQuery());
        }

        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
            $relation->select($relation->getQualifiedOwnerKeyName());

            return $builder->whereIn($relation->getQualifiedForeignKey(), $relation->getQuery());
        }

        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\MorphMany) {
            $relation->select($relation->getQualifiedForeignKeyName());
            $relation->where($relation->getMorphType(), $relation->getMorphClass());

            return $builder->whereIn($relation->getQualifiedParentKeyName(), $relation->getQuery());
        }

        throw new \Exception('不支持 '.get_class($relation).' 关系hasIn', 500);
    }
}
