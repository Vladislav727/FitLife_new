<?php

test('sleep duration calculated from hours and minutes', function () {
    $hours = 7;
    $minutes = 30;

    $duration = $hours + ($minutes / 60);

    expect($duration)->toBe(7.5);
});

test('sleep quality rating is within bounds', function () {
    $quality = 4;
    $minQuality = 1;
    $maxQuality = 5;

    $isValid = $quality >= $minQuality && $quality <= $maxQuality;

    expect($isValid)->toBeTrue();
});

test('sleep duration formats correctly', function () {
    $duration = 7.5;

    $hours = floor($duration);
    $minutes = ($duration - $hours) * 60;

    $formatted = sprintf('%dh %dm', $hours, $minutes);

    expect($formatted)->toBe('7h 30m');
});

test('zero sleep duration is valid edge case', function () {
    $duration = 0;

    expect($duration)->toBe(0);
});

test('sleep duration converts minutes to hours', function () {
    $totalMinutes = 450;

    $hours = $totalMinutes / 60;

    expect($hours)->toBe(7.5);
});

test('average sleep calculated correctly', function () {
    $sleepDurations = [7.0, 8.0, 6.5, 7.5];

    $average = array_sum($sleepDurations) / count($sleepDurations);

    expect($average)->toBe(7.25);
});
