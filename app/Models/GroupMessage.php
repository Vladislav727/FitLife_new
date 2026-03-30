<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = [
        'group_id', 'user_id', 'body', 'media_path', 'media_type',
        'audio_path', 'audio_duration',
        'edited_at', 'reply_to_id', 'forwarded_from_id', 'pinned_at',
        'file_path', 'file_name', 'file_size',
    ];

    protected function casts(): array
    {
        return [
            'edited_at' => 'datetime',
            'pinned_at' => 'datetime',
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

    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_to_id');
    }

    public function forwardedFrom()
    {
        return $this->belongsTo(self::class, 'forwarded_from_id');
    }

    public function reactions()
    {
        return $this->morphMany(MessageReaction::class, 'reactable');
    }

    public function favorites()
    {
        return $this->morphMany(MessageFavorite::class, 'message');
    }

    public function poll()
    {
        return $this->hasOne(GroupPoll::class);
    }
}
