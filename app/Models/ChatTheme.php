<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatTheme extends Model
{
    protected $fillable = ['user_id', 'chat_type', 'chat_id', 'theme_key'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chat()
    {
        return $this->morphTo();
    }
}
