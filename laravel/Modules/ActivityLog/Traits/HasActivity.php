<?php

namespace Modules\ActivityLog\Traits;

use Modules\Activitylog\Providers\ActivitylogServiceProvider;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin \Eloquent
 */
trait HasActivity
{
    use LogsActivity;

    public function actions(): MorphMany
    {
        return $this->morphMany(ActivitylogServiceProvider::determineActivityModel(), 'causer');
    }
}
