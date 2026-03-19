<?php

use App\Models\MealLog;
use App\Models\User;

test('guests cannot access food tracker', function () {
    $response = $this->get('/tracker/foods');

    $response->assertRedirect('/login');
});

test('authenticated users can access food tracker', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/tracker/foods');

    $response->assertOk();
});

test('food tracker displays today summary', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/tracker/foods');

    $response->assertOk();
    $response->assertViewHas('todaySummary');
});

test('users can calculate calories', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->postJson('/tracker/foods/calculate', [
            'items' => [
                [
                    'food' => 'Rice',
                    'quantity' => 200,
                    'meal' => 'Breakfast',
                    'calories_per_serving' => 130,
                    'serving_size' => 100,
                    'protein' => 2.7,
                    'fat' => 0.3,
                    'carbs' => 28.2,
                ],
            ],
        ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('meal_logs', [
        'user_id' => $user->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);
});

test('calories calculation for multiple foods', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->postJson('/tracker/foods/calculate', [
            'items' => [
                [
                    'food' => 'Rice',
                    'quantity' => 100,
                    'meal' => 'Breakfast',
                    'calories_per_serving' => 130,
                    'serving_size' => 100,
                    'protein' => 2.7,
                    'fat' => 0.3,
                    'carbs' => 28.2,
                ],
                [
                    'food' => 'Egg',
                    'quantity' => 100,
                    'meal' => 'Breakfast',
                    'calories_per_serving' => 155,
                    'serving_size' => 100,
                    'protein' => 13.0,
                    'fat' => 11.0,
                    'carbs' => 1.1,
                ],
                [
                    'food' => 'Chicken breast',
                    'quantity' => 150,
                    'meal' => 'Lunch',
                    'calories_per_serving' => 165,
                    'serving_size' => 100,
                    'protein' => 31.0,
                    'fat' => 3.6,
                    'carbs' => 0.0,
                ],
            ],
        ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('meal_logs', [
        'user_id' => $user->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'calories' => 130,
    ]);

    $this->assertDatabaseHas('meal_logs', [
        'user_id' => $user->id,
        'meal' => 'Breakfast',
        'food' => 'Egg',
        'calories' => 155,
    ]);

    $this->assertDatabaseHas('meal_logs', [
        'user_id' => $user->id,
        'meal' => 'Lunch',
        'food' => 'Chicken breast',
    ]);
});

test('food calculation requires items array', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/tracker/foods/calculate', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('items');
});

test('empty meals return error', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/tracker/foods/calculate', [
        'items' => [
            [
                'food' => '',
                'quantity' => 0,
                'meal' => 'Breakfast',
            ],
        ],
    ]);

    $response->assertUnprocessable();
});

test('meal logs are displayed in food tracker', function () {
    $user = User::factory()->create();

    MealLog::create([
        'user_id' => $user->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);

    $response = $this->actingAs($user)->get('/tracker/foods');

    $response->assertOk();
    $response->assertViewHas('mealLogs');
});

test('users only see their own meal logs', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    MealLog::create([
        'user_id' => $user1->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);

    MealLog::create([
        'user_id' => $user2->id,
        'meal' => 'Lunch',
        'food' => 'Chicken breast',
        'quantity' => 150,
        'calories' => 248,
    ]);

    $response = $this->actingAs($user1)->get('/tracker/foods');

    $logs = $response->viewData('mealLogs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->user_id)->toBe($user1->id);
});

test('users can delete their own meal log', function () {
    $user = User::factory()->create();

    $log = MealLog::create([
        'user_id' => $user->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);

    $response = $this->actingAs($user)->deleteJson("/tracker/foods/log/{$log->id}");

    $response->assertOk();
    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('meal_logs', ['id' => $log->id]);
});

test('users cannot delete other users meal log', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $log = MealLog::create([
        'user_id' => $user2->id,
        'meal' => 'Breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);

    $response = $this->actingAs($user1)->deleteJson("/tracker/foods/log/{$log->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('meal_logs', ['id' => $log->id]);
});
