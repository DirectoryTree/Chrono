<?php

use DirectoryTree\Chrono\Chrono;

it('parses dutch relative date period expressions', function () {
    $dutch = Chrono::nl();
    $nextMonth = $dutch->parse('volgende maand', '2016-10-01 12:00')[0];
    $upcomingMonth = $dutch->parse('aankomende maand', '2016-10-01 12:00')[0];
    $nextYear = $dutch->parse('volgend jaar', '2020-11-22 12:11:32.006')[0];

    expect($dutch->date('deze week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-05 09:30:00')
        ->and($dutch->date('deze maand', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-01 09:30:00')
        ->and($dutch->date('dit jaar', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-01-01 09:30:00')
        ->and($dutch->date('afgelopen week', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-24 12:00:00')
        ->and($dutch->date('afgelopen maand', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-01 12:00:00')
        ->and($dutch->date('afgelopen dag', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-30 12:00:00')
        ->and($dutch->date('vorige week', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-24 12:00:00')
        ->and($dutch->date('komend uur', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-01 13:00:00')
        ->and($dutch->date('volgende week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($dutch->date('volgende dag', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-02 12:00:00')
        ->and($dutch->date('vorige maand', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-07-10 09:30:00')
        ->and($nextMonth->start->date()->toDateTimeString())
        ->toBe('2016-11-01 12:00:00')
        ->and($nextMonth->start->isCertain('day'))->toBeFalse()
        ->and($nextMonth->start->isCertain('hour'))->toBeFalse()
        ->and($upcomingMonth->start->date()->toDateTimeString())
        ->toBe('2016-11-01 12:00:00')
        ->and($upcomingMonth->start->isCertain('day'))->toBeFalse()
        ->and($upcomingMonth->start->isCertain('hour'))->toBeFalse()
        ->and($nextYear->start->date()->format('Y-m-d H:i:s.v'))
        ->toBe('2021-11-22 12:11:32.006')
        ->and($nextYear->start->isCertain('year'))->toBeTrue()
        ->and($nextYear->start->isCertain('month'))->toBeFalse()
        ->and($nextYear->start->isCertain('day'))->toBeFalse()
        ->and($nextYear->start->isCertain('hour'))->toBeFalse()
        ->and($nextYear->start->isCertain('minute'))->toBeFalse()
        ->and($nextYear->start->isCertain('second'))->toBeFalse()
        ->and($nextYear->start->isCertain('millisecond'))->toBeFalse()
        ->and($nextYear->start->isCertain('timezoneOffset'))->toBeFalse();
});
