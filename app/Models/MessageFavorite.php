<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageFavorite extends Model
{
    protected $fillable = ['user_id', 'message_type', 'message_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->morphTo();
    }
}
