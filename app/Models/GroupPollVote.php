<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPollVote extends Model
{
    protected $fillable = ['group_poll_id', 'group_poll_option_id', 'user_id'];

    public function poll()
    {
        return $this->belongsTo(GroupPoll::class, 'group_poll_id');
    }

    public function option()
    {
        return $this->belongsTo(GroupPollOption::class, 'group_poll_option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
