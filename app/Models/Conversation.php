<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ConversationMessage::class)->latestOfMany();
    }

    public function otherUser(User $user)
    {
        return $this->user_one_id === $user->id ? $this->userTwo : $this->userOne;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
    }

    public function pinnedMessages()
    {
        return $this->messages()->whereNotNull('pinned_at')->orderByDesc('pinned_at');
    }
}
