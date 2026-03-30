<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'media_path',
        'media_type',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function getViewCount(): int
    {
        return $this->postViews()->count();
    }

    public function isLikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->where('type', 'post')->where('is_like', true)->exists();
    }

    public function isDislikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->where('type', 'post')->where('is_like', false)->exists();
    }
}
