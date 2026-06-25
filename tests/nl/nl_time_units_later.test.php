<?php

use Chrono\Chrono;

it('parses dutch later relative durations', function () {
    $dutch = Chrono::nl();
    $fromNow = $dutch->parseText('5 dagen vanaf nu', '2012-08-10 00:00')[0];
    $minutesFromNow = $dutch->parseText('15 minuten vanaf nu', '2012-08-10 12:14')[0];
    $minutesOut = $dutch->parseText('15 minuten uit', '2012-08-10 12:14')[0];
    $secondsFromNow = $dutch->parseText('Over 12 seconden', '2012-08-10 12:14')[0];

    expect($fromNow->text)->toBe('5 dagen vanaf nu')
        ->and($fromNow->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($minutesFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($minutesOut->text)->toBe('15 minuten uit')
        ->and($minutesOut->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($secondsFromNow->text)->toBe('Over 12 seconden')
        ->and($secondsFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:12');
});
