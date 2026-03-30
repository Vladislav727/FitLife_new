<?php

test('calories are calculated correctly per 100g', function () {
    $caloriesPer100g = 130;
    $quantity = 200;

    $calories = round($caloriesPer100g * ($quantity / 100));

    expect($calories)->toEqual(260);
});

test('calories round to nearest integer', function () {
    $caloriesPer100g = 165;
    $quantity = 150;

    $calories = round($caloriesPer100g * ($quantity / 100));

    expect($calories)->toEqual(248);
});

test('zero quantity returns zero calories', function () {
    $caloriesPer100g = 500;
    $quantity = 0;

    $calories = round($caloriesPer100g * ($quantity / 100));

    expect($calories)->toEqual(0);
});

test('small quantities calculate correctly', function () {
    $caloriesPer100g = 52;
    $quantity = 50;

    $calories = round($caloriesPer100g * ($quantity / 100));

    expect($calories)->toEqual(26);
});
