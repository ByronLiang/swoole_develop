<?php

namespace Modules\Comment\Entities;

class CommentGable extends \App\Models\Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function able()
    {
        return $this->morphTo('gable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Comment
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
