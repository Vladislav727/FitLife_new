<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPoll extends Model
{
    protected $fillable = [
        'group_id', 'user_id', 'group_message_id',
        'question', 'is_anonymous', 'is_multiple', 'closes_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'is_multiple' => 'boolean',
            'closes_at' => 'datetime',
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(GroupMessage::class, 'group_message_id');
    }

    public function options()
    {
        return $this->hasMany(GroupPollOption::class)->orderBy('sort_order');
    }

    public function votes()
    {
        return $this->hasMany(GroupPollVote::class);
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }

    public function isClosed(): bool
    {
        return $this->closes_at && $this->closes_at->isPast();
    }
}
