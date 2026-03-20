<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInvite extends Model
{
    protected $fillable = ['group_id', 'sender_id', 'user_id', 'status'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
