<?php

use Chrono\Chrono;

it('merges spanish dates with times and date ranges', function () {
    $spanish = Chrono::es();
    $dateTime = $spanish->parseText('Evento 10 Agosto 2012, 6pm', '2012-08-10')[0];
    $dateTimeWithA = $spanish->parseText('Evento 10 Agosto 2012 a 6pm', '2012-08-10')[0];
    $dateRange = $spanish->parseText('Evento 10/08/2012 - 12/08/2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 Agosto 2012, 6pm')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateTimeWithA->text)->toBe('10 Agosto 2012 a 6pm')
        ->and($dateTimeWithA->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($dateRange->text)->toBe('10/08/2012 - 12/08/2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange');
});
