<?php

namespace Modules\ReceivingPlace\Entities;

use Modules\DistrictGable\DistrictGableTrait;

class ReceivingPlace extends \App\Models\Model
{
    use DistrictGableTrait;

    protected $hidden = [
        'able_id',
        'able_type',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(new ReceivingPlaceEvent());
    }

    public function able()
    {
        return $this->morphTo();
    }

    /**
     * district_str.
     *
     * @return string
     */
    public function getDistrictStrAttribute()
    {
        $districts = $this->relationLoaded('districts') ? $this->districts : $this->districts();

        return $districts->pluck('full_name')->implode('');
    }

    /**
     * full_address.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return $this->district_str.$this->address;
    }
}
