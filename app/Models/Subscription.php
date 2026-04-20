<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'subscribed_user_id',
        'status',
    ];

    public $timestamps = true;

    public function subscriber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscribedUser()
    {
        return $this->belongsTo(User::class, 'subscribed_user_id');
    }
}