<?php

use App\Models\User;

test('authenticated users can open the calorie calculator page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/calorie-calculator');

    $response->assertOk();
    $response->assertViewIs('calories.index');
});

test('legacy calories url redirects to the calorie calculator page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/calories');

    $response->assertRedirect(route('calories.index'));
});

test('users can calculate calories from the calorie calculator page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/calorie-calculator', [
        'weight' => 82,
        'height' => 180,
        'age' => 29,
        'activity_level' => 'moderate',
        'goal_type' => 'maintain',
    ]);

    $response->assertOk();
    $response->assertViewIs('calories.index');
    $response->assertViewHasAll(['calories', 'protein', 'fat', 'carbs', 'todayCalories']);
});