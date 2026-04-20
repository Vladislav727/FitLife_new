<?php

use App\Models\Subscription;
use App\Models\User;

test('guests cannot send subscription requests', function () {
    $user = User::factory()->create();

    $response = $this->post("/subscriptions/{$user->username}");

    $response->assertRedirect('/login');
});

test('users can send subscription requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user1)->post("/subscriptions/{$user2->username}");

    $response->assertStatus(201);
    $response->assertJson(['action' => 'request_sent']);

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);
});

test('users cannot send subscription request to themselves', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post("/subscriptions/{$user->username}");

    $response->assertStatus(400);
    $response->assertJson(['error' => 'Cannot subscribe to yourself']);
});

test('users cannot send duplicate subscription requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user1)->post("/subscriptions/{$user2->username}");

    $response->assertStatus(400);
});

test('users can accept subscription requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user2)->post("/subscriptions/{$user1->username}/accept");

    $response->assertOk();
    $response->assertJson(['action' => 'accepted']);

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'accepted',
    ]);
});

test('users cannot accept non-existent subscription requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user2)->post("/subscriptions/{$user1->username}/accept");

    $response->assertStatus(404);
});

test('users can remove subscriptions', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'accepted',
    ]);

    Subscription::create([
        'user_id' => $user2->id,
        'subscribed_user_id' => $user1->id,
        'status' => 'accepted',
    ]);

    $response = $this->actingAs($user1)->delete("/subscriptions/{$user2->username}");

    $response->assertOk();
    $response->assertJson(['action' => 'removed']);

    $this->assertDatabaseMissing('subscriptions', [
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'accepted',
    ]);
});

test('users cannot remove non-existent subscriptions', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user1)->delete("/subscriptions/{$user2->username}");

    $response->assertStatus(404);
});

test('accepting subscription request creates reciprocal subscription', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Subscription::create([
        'user_id' => $user1->id,
        'subscribed_user_id' => $user2->id,
        'status' => 'pending',
    ]);

    $this->actingAs($user2)->post("/subscriptions/{$user1->username}/accept");

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user2->id,
        'subscribed_user_id' => $user1->id,
        'status' => 'accepted',
    ]);
});