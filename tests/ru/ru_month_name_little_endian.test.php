<?php

use Chrono\Chrono;

it('parses russian month name dates and ranges', function () {
    $date = Chrono::ru()->parseText('10 августа 2012', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10-12 августа', '2012-08-10 09:30')[0];
    $crossMonthWithYear = Chrono::ru()->parseText('10 августа - 12 сентября 2013', '2012-08-10 09:30')[0];
    $month = Chrono::ru()->parseText('август 2012', '2012-08-10 09:30')[0];

    expect($date->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/RUMonthNameParser');
});
