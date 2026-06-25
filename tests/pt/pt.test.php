<?php

use Chrono\Chrono;

it('merges portuguese dates with times and date ranges', function () {
    $portuguese = Chrono::pt();
    $dateTime = $portuguese->parseText('10 de agosto de 2012 às 6:30', '2012-08-10')[0];
    $dateRange = $portuguese->parseText('10 de agosto - 12 de agosto', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 de agosto de 2012 às 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateRange->text)->toBe('10 de agosto - 12 de agosto')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange');
});
