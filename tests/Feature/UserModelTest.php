<?php

use App\Models\Biography;
use App\Models\Calendar;
use App\Models\Friend;
use App\Models\Goal;
use App\Models\Post;
use App\Models\Sleep;
use App\Models\User;

test('user has name attribute', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    expect($user->name)->toBe('John Doe');
});

test('user has email attribute', function () {
    $user = User::factory()->create(['email' => 'john@example.com']);

    expect($user->email)->toBe('john@example.com');
});

test('user has username attribute', function () {
    $user = User::factory()->create(['username' => 'johndoe123']);

    expect($user->username)->toBe('johndoe123');
});

test('user password is hashed', function () {
    $user = User::factory()->create(['password' => 'password']);

    expect($user->password)->not->toBe('password');
});

test('user has biography relationship', function () {
    $user = User::factory()->create();
    Biography::create([
        'user_id' => $user->id,
        'full_name' => 'John Doe',
    ]);

    expect($user->biography)->not->toBeNull();
    expect($user->biography->full_name)->toBe('John Doe');
});

test('user has posts relationship', function () {
    $user = User::factory()->create();
    Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    expect($user->posts)->toHaveCount(1);
});

test('user has goals relationship', function () {
    $user = User::factory()->create();
    Goal::create([
        'user_id' => $user->id,
        'type' => 'weight',
        'target_value' => 70,
        'current_value' => 0,
        'end_date' => now()->addMonth(),
    ]);

    expect($user->goals)->toHaveCount(1);
});

test('user has sleeps relationship', function () {
    $user = User::factory()->create();
    Sleep::create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => '23:00',
        'end_time' => '07:00',
        'duration' => 8,
    ]);

    expect($user->sleeps)->toHaveCount(1);
});

test('user has calendars relationship', function () {
    $user = User::factory()->create();
    Calendar::create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'type' => 'workout',
        'description' => 'Test',
        'completed' => false,
    ]);

    expect($user->calendars)->toHaveCount(1);
});

test('user can check friendship status', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user1->isFriendWith($user2))->toBeFalse();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'accepted',
    ]);

    expect($user1->isFriendWith($user2))->toBeTrue();
});

test('user can check pending request to another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user1->hasPendingRequestTo($user2))->toBeFalse();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($user1->hasPendingRequestTo($user2))->toBeTrue();
});

test('user can check pending request from another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user2->hasPendingRequestFrom($user1))->toBeFalse();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($user2->hasPendingRequestFrom($user1))->toBeTrue();
});

test('user can check role', function () {
    $user = User::factory()->create(['role' => 'admin']);

    expect($user->hasRole('admin'))->toBeTrue();
    expect($user->hasRole('user'))->toBeFalse();
});

test('user friends relationship returns only accepted friends', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user3->id,
        'status' => 'accepted',
    ]);

    expect($user1->friends)->toHaveCount(1);
    expect($user1->friends->first()->id)->toBe($user3->id);
});
