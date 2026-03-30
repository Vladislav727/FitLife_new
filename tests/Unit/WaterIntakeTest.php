<?php

test('water intake percentage of daily goal', function () {
    $intake = 1500;
    $dailyGoal = 2000;

    $percentage = ($intake / $dailyGoal) * 100;

    expect($percentage)->toBe(75.0);
});

test('water intake goal reached', function () {
    $intake = 2500;
    $dailyGoal = 2000;

    $isReached = $intake >= $dailyGoal;

    expect($isReached)->toBeTrue();
});

test('water intake converts ml to liters', function () {
    $milliliters = 2500;

    $liters = $milliliters / 1000;

    expect($liters)->toBe(2.5);
});

test('water intake sum calculated correctly', function () {
    $intakes = [250, 300, 500, 250, 200];

    $total = array_sum($intakes);

    expect($total)->toBe(1500);
});

test('remaining water to goal calculated', function () {
    $intake = 1200;
    $dailyGoal = 2000;

    $remaining = max(0, $dailyGoal - $intake);

    expect($remaining)->toBe(800);
});

test('remaining water is zero when goal exceeded', function () {
    $intake = 2500;
    $dailyGoal = 2000;

    $remaining = max(0, $dailyGoal - $intake);

    expect($remaining)->toBe(0);
});
