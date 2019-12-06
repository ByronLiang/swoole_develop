<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Model;

class Comment extends Model
{
    use SoftDeletes;

    public function target()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo();
    }

    /**
     * @param Model $target
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
    public function createComment(Model $target, $data, Model $creator): self
    {
        if (method_exists($target, 'comments')) {
            return $target->comments()->create(array_merge($data, [
                'user_id' => $creator->getAuthIdentifier(),
                'user_type' => get_class($creator),
            ]));
        }

        return null;
    }
}
