<?php

namespace Modules\TagGable\Entities;

class TagGable extends \App\Models\Model
{
    public $timestamps = false;

    protected $casts = [
        'extend' => 'json',
    ];

    public function tags()
    {
        return $this->belongsTo(Tag::class);
    }
}
