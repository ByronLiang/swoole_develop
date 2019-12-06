<?php

namespace App\Models;

class ChatRecord extends Model
{
    protected $casts = [
        'content' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
