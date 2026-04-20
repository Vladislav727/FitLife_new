<?php

use App\Models\Biography;
use App\Models\Calendar;
use App\Models\Goal;
use App\Models\Post;
use App\Models\Sleep;
use App\Models\Subscription;
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

test('user can check subscription status', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user1->hasSubscriptionWith($user2))->toBeFalse();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'accepted',
    ]);

    expect($user1->hasSubscriptionWith($user2))->toBeTrue();
});

test('user can check pending subscription to another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user1->hasPendingSubscriptionTo($user2))->toBeFalse();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($user1->hasPendingSubscriptionTo($user2))->toBeTrue();
});

test('user can check pending subscription from another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    expect($user2->hasPendingSubscriptionFrom($user1))->toBeFalse();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($user2->hasPendingSubscriptionFrom($user1))->toBeTrue();
});

test('user can check role', function () {
    $user = User::factory()->create(['role' => 'admin']);

    expect($user->hasRole('admin'))->toBeTrue();
    expect($user->hasRole('user'))->toBeFalse();
});

test('user subscriptions relationship returns only accepted subscriptions', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user3->id,
        'status' => 'accepted',
    ]);

    expect($user1->subscriptions)->toHaveCount(1);
    expect($user1->subscriptions->first()->id)->toBe($user3->id);
});
