<?php

namespace App\Models;

class Author extends Model
{
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function room()
    {
        return $this->hasOne(AuthorChatRoom::class);
    }
}
