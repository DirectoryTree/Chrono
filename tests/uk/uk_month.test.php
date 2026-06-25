<?php

use Chrono\Chrono;

it('parses ukrainian month expressions', function () {
    $dateTime = Chrono::uk()->parseText('10 серпня 2012 о 6:30 вечора', '2012-08-10 09:30')[0];
    $range = Chrono::uk()->parseText('10 серпня - 12 серпня', '2012-08-10 09:30')[0];

    expect($dateTime->text)->toBe('10 серпня 2012 о 6:30 вечора')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
