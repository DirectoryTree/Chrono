<?php

use Chrono\Chrono;

it('parses russian month expressions', function () {
    $dateTime = Chrono::ru()->parseText('10 августа 2012 в 6:30 вечера', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10 августа - 12 августа', '2012-08-10 09:30')[0];

    expect($dateTime->text)->toBe('10 августа 2012 в 6:30 вечера')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
