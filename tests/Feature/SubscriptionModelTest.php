<?php

use App\Models\Subscription;
use App\Models\User;

test('subscription has subscriber relationship', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $subscription = Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($subscription->subscriber->id)->toBe($user1->id);
});

test('subscription has subscribed user relationship', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $subscription = Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($subscription->subscribedUser->id)->toBe($user2->id);
});

test('subscription has status pending', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $subscription = Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    expect($subscription->status)->toBe('pending');
});

test('subscription status can be accepted', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $subscription = Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    $subscription->update(['status' => 'accepted']);

    expect($subscription->status)->toBe('accepted');
});

test('subscription can be deleted', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $subscription = Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'accepted',
    ]);

    $subscriptionId = $subscription->id;
    $subscription->delete();

    expect(Subscription::find($subscriptionId))->toBeNull();
});