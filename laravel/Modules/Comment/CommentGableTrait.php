<?php

namespace Modules\Comment;

use Modules\Comment\Entities\Comment;
use Modules\Comment\Entities\CommentGable;

/**
 * Trait Taggable.
 *
 * @method static static WithAnyTag($tagNames)
 * @method static static WithoutTags($tagNames)
 * @mixin \Eloquent
 */
trait CommentGableTrait
{
    public static function bootCommentGableTrait()
    {
        static::deleting(function (self $model) {
            if (null === $model->forceDeleting || true === $model->forceDeleting) {
                $model->comments()->sync([]);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Comment
     */
    public function comments()
    {
        return $this->morphToMany(Comment::class, 'gable', 'comment_gables');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function commentGables()
    {
        return $this->morphMany(CommentGable::class, 'gable');
    }
}
