<?php

use Chrono\Chrono;

it('parses vietnamese date ranges', function () {
    $range = Chrono::vi()->parseText('30/04/1975 - 01/05/1975', '2012-08-10')[0];

    expect($range->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('1975-05-01 12:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange');
});
