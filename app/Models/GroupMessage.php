<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = ['group_id', 'user_id', 'body', 'media_path', 'media_type', 'edited_at'];

    protected function casts(): array
    {
        return ['edited_at' => 'datetime'];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
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
