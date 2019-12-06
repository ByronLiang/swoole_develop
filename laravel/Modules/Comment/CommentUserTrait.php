<?php

namespace Modules\Comment;

use Modules\Comment\Entities\Comment;

/**
 * @mixin \Eloquent
 */
trait CommentUserTrait
{
    public function comment()
    {
        return $this->morphOne(Comment::class, 'user');
    }
}
