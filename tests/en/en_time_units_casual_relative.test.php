<?php

use Chrono\Chrono;

it('parses signed relative durations', function () {
    expect(Chrono::parseDate('+15min', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and(Chrono::parseDate('+1 day 2 hour', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-11 14:14:00')
        ->and(Chrono::parseDate('-3y', '2015-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:14:00')
        ->and(Chrono::parseDate('+1qtr', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-01-01 12:00:00')
        ->and(Chrono::parseDate('-2hr5min', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-01 09:55:00')
        ->and(Chrono::parseDate('-5d 00', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-26 00:00:00');
});
