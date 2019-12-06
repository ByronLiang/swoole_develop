<?php

namespace Modules\Comment;

use Modules\Comment\Entities\Comment;
use App\Models\Model;

/**
 * @mixin \Eloquent
 */
trait CommentTargetTrait
{
    public static function bootCommentTargetTrait()
    {
        static::deleting(function (self $model) {
            if (null === $model->forceDeleting || true === $model->forceDeleting) {
                $model->comments()->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|Comment
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|Comment
     */
    public function comment()
    {
        return $this->morphOne(Comment::class, 'target');
    }

    /**
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
    public function replyComment($data, Model $creator, Comment $replyComment = null)
    {
        $comment = (new Comment())->createComment($this, $data, $creator);
        if ($replyComment) {
            $comment->update(['reply_id' => $replyComment->id]);
        }

        return $comment;
    }
}
