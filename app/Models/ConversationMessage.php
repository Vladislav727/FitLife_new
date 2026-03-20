<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'body', 'read_at', 'media_path', 'media_type', 'edited_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime', 'edited_at' => 'datetime'];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->morphMany(MessageReaction::class, 'reactable');
    }
}
