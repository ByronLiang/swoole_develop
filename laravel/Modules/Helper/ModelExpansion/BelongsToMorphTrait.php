<?php

namespace Modules\Helper\ModelExpansion;

/**
 * $this->>morphTo()->forClass($class).
 *
 * @mixin \Eloquent
 */
trait BelongsToMorphTrait
{
    public static function bootBelongsToMorphTrait()
    {
        \Illuminate\Database\Eloquent\Relations\MorphTo::macro('forClass', function ($class) {
            return BelongsToMorph::build($this->getParent(), $class, $this->getRelation());
        });
    }
}
