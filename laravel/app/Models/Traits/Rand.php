<?php

namespace App\Models\Traits;

/**
 * @method static|self rand()
 * @mixin \Eloquent
 */
trait Rand
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRand(\Illuminate\Database\Eloquent\Builder $query)
    {
        $min = self::selectRaw('MIN(id)')->toSql();
        $max = self::selectRaw('MAX(id)')->toSql();

        return $query
            ->where('id', '>=', \DB::raw("(($max)-($min)) * RAND() + ($min)"));
    }
}
