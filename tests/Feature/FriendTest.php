<?php

use App\Models\Friend;
use App\Models\User;

test('guests cannot send friend requests', function () {
    $user = User::factory()->create();

    $response = $this->post("/friends/{$user->username}");

    $response->assertRedirect('/login');
});

test('users can send friend requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user1)->post("/friends/{$user2->username}");

    $response->assertStatus(201);
    $response->assertJson(['action' => 'request_sent']);

    $this->assertDatabaseHas('friends', [
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);
});

test('users cannot send friend request to themselves', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post("/friends/{$user->username}");

    $response->assertStatus(400);
    $response->assertJson(['error' => 'Cannot add yourself as a friend']);
});

test('users cannot send duplicate friend requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user1)->post("/friends/{$user2->username}");

    $response->assertStatus(400);
});

test('users can accept friend requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user2)->post("/friends/{$user1->username}/accept");

    $response->assertOk();
    $response->assertJson(['action' => 'accepted']);

    $this->assertDatabaseHas('friends', [
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'accepted',
    ]);
});

test('users cannot accept non-existent friend requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user2)->post("/friends/{$user1->username}/accept");

    $response->assertStatus(404);
});

test('users can remove friends', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'accepted',
    ]);

    Friend::create([
        'user_id' => $user2->id,
        'friend_id' => $user1->id,
        'status' => 'accepted',
    ]);

    $response = $this->actingAs($user1)->delete("/friends/{$user2->username}");

    $response->assertOk();
    $response->assertJson(['action' => 'removed']);

    $this->assertDatabaseMissing('friends', [
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'accepted',
    ]);
});

test('users cannot remove non-existent friendships', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user1)->delete("/friends/{$user2->username}");

    $response->assertStatus(404);
});

test('accepting friend request creates reciprocal friendship', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Friend::create([
        'user_id' => $user1->id,
        'friend_id' => $user2->id,
        'status' => 'pending',
    ]);

    $this->actingAs($user2)->post("/friends/{$user1->username}/accept");

    $this->assertDatabaseHas('friends', [
        'user_id' => $user2->id,
        'friend_id' => $user1->id,
        'status' => 'accepted',
    ]);
});
