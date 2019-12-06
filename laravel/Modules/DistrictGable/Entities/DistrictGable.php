<?php

namespace Modules\DistrictGable\Entities;

class DistrictGable extends \App\Models\Model
{
    protected $casts = [
        'extend' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function able()
    {
        return $this->morphTo('gable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|District
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
