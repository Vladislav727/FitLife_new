<?php

use App\Models\Calendar;
use App\Models\MealLog;
use App\Models\Sleep;
use App\Models\User;
use App\Models\WaterLog;

test('guests cannot access dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('authenticated users can access dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
});

test('dashboard displays user statistics', function () {
    $user = User::factory()->create();

    MealLog::create([
        'user_id' => $user->id,
        'meal' => 'breakfast',
        'food' => 'Rice',
        'quantity' => 200,
        'calories' => 260,
    ]);

    Sleep::create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => '23:00',
        'end_time' => '07:00',
        'duration' => 8,
    ]);

    WaterLog::create([
        'user_id' => $user->id,
        'amount' => 500,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertViewHas('totalCalories');
    $response->assertViewHas('totalSleep');
    $response->assertViewHas('totalWater');
});

test('dashboard shows upcoming events', function () {
    $user = User::factory()->create();

    Calendar::create([
        'user_id' => $user->id,
        'date' => now()->addDays(2)->toDateString(),
        'type' => 'workout',
        'description' => 'Morning workout',
        'completed' => false,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertViewHas('upcomingEvents');
});
