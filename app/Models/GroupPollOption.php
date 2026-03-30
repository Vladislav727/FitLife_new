<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPollOption extends Model
{
    public $timestamps = false;

    protected $fillable = ['group_poll_id', 'text', 'sort_order'];

    public function poll()
    {
        return $this->belongsTo(GroupPoll::class, 'group_poll_id');
    }

    public function votes()
    {
        return $this->hasMany(GroupPollVote::class);
    }
}
