<?php

namespace Modules\TagGable\Entities;

class TagGroup extends \App\Models\Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'sort_order',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Tag
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
