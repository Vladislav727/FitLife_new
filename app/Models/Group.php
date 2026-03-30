<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['owner_id', 'name', 'description', 'avatar'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')->withPivot('role')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function invites()
    {
        return $this->hasMany(GroupInvite::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(GroupMessage::class)->latestOfMany();
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('group_members.user_id', $user->id)->exists();
    }

    public function getMemberRole(User $user): ?string
    {
        return $this->members()->where('group_members.user_id', $user->id)->value('group_members.role');
    }

    public function isAdmin(User $user): bool
    {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner', 'admin']);
    }

    public function pinnedMessages()
    {
        return $this->messages()->whereNotNull('pinned_at')->orderByDesc('pinned_at');
    }
}
