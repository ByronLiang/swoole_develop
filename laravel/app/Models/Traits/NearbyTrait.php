<?php

namespace App\Models\Traits;

/**
 * @method static static filter($latitude, $longitude, $radius = null)
 */
trait NearbyTrait
{
    /**
     * 附近排序.
     *
     * @param $query
     * @param float    $latitude
     * @param float    $longitude
     * @param int|null $radius    半径单位：千米
     *
     * @return mixed
     */
    public function scopeNearby(\Illuminate\Database\Eloquent\Builder $query, $latitude, $longitude, $radius = null)
    {
        $distance_sql = 'round(6371008 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance';

        return $query->addSelect('*')
            ->selectRaw($distance_sql, [$latitude, $longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->when($radius, function (\Illuminate\Database\Query\Builder $q) use ($latitude, $longitude, $radius) {
                $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 111.045);
                $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 111.045);
                $lat_min = $latitude - ($radius / 111.045);
                $lat_max = $latitude + ($radius / 111.045);

                return $q->whereBetween('latitude', [$lat_min, $lat_max])
                    ->whereBetween('longitude', [$lng_min, $lng_max]);
            });
    }
}
