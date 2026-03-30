<?php

test('BMI calculated correctly', function () {
    $weight = 70;
    $height = 175;

    $heightInMeters = $height / 100;
    $bmi = $weight / ($heightInMeters * $heightInMeters);

    expect(round($bmi, 1))->toBe(22.9);
});

test('BMI category underweight', function () {
    $bmi = 17.5;

    $category = match (true) {
        $bmi < 18.5 => 'underweight',
        $bmi < 25 => 'normal',
        $bmi < 30 => 'overweight',
        default => 'obese',
    };

    expect($category)->toBe('underweight');
});

test('BMI category normal', function () {
    $bmi = 22.0;

    $category = match (true) {
        $bmi < 18.5 => 'underweight',
        $bmi < 25 => 'normal',
        $bmi < 30 => 'overweight',
        default => 'obese',
    };

    expect($category)->toBe('normal');
});

test('BMI category overweight', function () {
    $bmi = 27.0;

    $category = match (true) {
        $bmi < 18.5 => 'underweight',
        $bmi < 25 => 'normal',
        $bmi < 30 => 'overweight',
        default => 'obese',
    };

    expect($category)->toBe('overweight');
});

test('BMI category obese', function () {
    $bmi = 32.0;

    $category = match (true) {
        $bmi < 18.5 => 'underweight',
        $bmi < 25 => 'normal',
        $bmi < 30 => 'overweight',
        default => 'obese',
    };

    expect($category)->toBe('obese');
});

test('BMR calculation for male', function () {
    $weight = 70;
    $height = 175;
    $age = 25;
    $gender = 'male';

    $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;

    expect(round($bmr))->toEqual(1674);
});

test('BMR calculation for female', function () {
    $weight = 60;
    $height = 165;
    $age = 25;
    $gender = 'female';

    $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;

    expect(round($bmr))->toEqual(1345);
});
