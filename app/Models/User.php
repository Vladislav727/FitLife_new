<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'banner',
        'weight',
        'height',
        'age',
        'activity_level',
        'goal_type',
        'role',
        'bio',
        'language',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    public function biography()
    {
        return $this->hasOne(Biography::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function sleeps()
    {
        return $this->hasMany(Sleep::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function goalLogs()
    {
        return $this->hasManyThrough(GoalLog::class, Goal::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'subscribed_user_id')
            ->wherePivot('status', 'accepted')
            ->withPivot('status')
            ->distinct();
    }

    public function sentSubscriptionRequests()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function receivedSubscriptionRequests()
    {
        return $this->hasMany(Subscription::class, 'subscribed_user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->followings()->where('following_id', $user->id)->exists();
    }

    public function mealLogs()
    {
        return $this->hasMany(MealLog::class);
    }

    public function waterLogs()
    {
        return $this->hasMany(WaterLog::class);
    }

    public function hasSubscriptionWith(User $user): bool
    {
        return $this->subscriptions()->where('subscribed_user_id', $user->id)->exists();
    }

    public function hasPendingSubscriptionTo(User $user): bool
    {
        return $this->sentSubscriptionRequests()
            ->where('subscribed_user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function hasPendingSubscriptionFrom(User $user): bool
    {
        return $this->receivedSubscriptionRequests()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members')->withPivot('role')->withTimestamps();
    }

    public function groupInvites()
    {
        return $this->hasMany(GroupInvite::class, 'user_id')->where('status', 'pending');
    }

    public function isMutualFollow(User $user): bool
    {
        return $this->isFollowing($user) && $user->isFollowing($this);
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(3));
    }
}
