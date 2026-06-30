<?php

use DirectoryTree\Chrono\Chrono;

it('merges finnish dates with times and date ranges', function () {
    $finnish = Chrono::fi();
    $dateTime = $finnish->parseText('Nähdään 10. elokuuta 2012 klo 6:30', '2012-08-10')[0];
    $timeRange = $finnish->parseText('Nähdään 10. elokuuta 2012 klo 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $finnish->parseText('Tapahtuma 10. elokuuta 2012 - 12. elokuuta 2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10. elokuuta 2012 klo 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10. elokuuta 2012 klo 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10. elokuuta 2012 - 12. elokuuta 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
