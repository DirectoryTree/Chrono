<?php

use DirectoryTree\Chrono\Chrono;

it('parses relative dates', function () {
    $result = Chrono::parse('5 days ago', '2026-06-23 09:15:30')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2026-06-18 09:15:30')
        ->and($result->start->get('day'))->toBe(18)
        ->and($result->start->isCertain('day'))->toBeTrue()
        ->and($result->tags())->toContain('result/relativeDate');
});

it('parses relative dates with abbreviated units', function () {
    $date = Chrono::parseDate('3w later', '2026-06-23 09:15:30');

    expect($date?->toDateTimeString())->toBe('2026-07-14 09:15:30');
});

it('does not parse for the unit phases as relative dates', function () {
    expect(Chrono::parse('for the year', '2026-06-23 09:15:30'))
        ->toBe([]);
});

it('parses relative duration aliases and decimal amounts', function () {
    expect(Chrono::parse('5 days from now, we did something', '2012-08-10 00:00')[0]->text)
        ->toBe('5 days from now')
        ->and(Chrono::parseDate('15 minutes earlier', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and(Chrono::parseDate('15 minute out', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:29:00')
        ->and(Chrono::parseDate('3 quarters ago', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2011-11-10 12:14:00')
        ->and(Chrono::parseDate('2 qtrs later', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-02-10 12:14:00')
        ->and(Chrono::parseDate('in 1.5 hours', '2012-08-10 12:40')?->toDateTimeString())
        ->toBe('2012-08-10 14:10:00');
});

it('rejects abbreviated relative units in strict mode', function () {
    $strict = Chrono::strict();

    expect($strict->parseDateText('in 2hour', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 16:52:00')
        ->and($strict->parseText('in 15m', '2016-10-01 14:52'))->toBe([])
        ->and($strict->parseText('within 5hr', '2016-10-01 14:52'))->toBe([])
        ->and($strict->parseText('5m ago', '2016-10-01 14:52'))->toBe([]);
});

it('parses multiple relative time units', function () {
    $date = Chrono::parseDate('set a timer for 1 hour, 5 minutes, and 30 seconds', '2026-06-23 09:15:30');

    expect($date?->toDateTimeString())->toBe('2026-06-23 10:21:00');
});

it('parses bare relative durations with the forward date option', function () {
    $month = Chrono::parse('give it 2 months', '2016-10-01 14:52:00', ['forwardDate' => true])[0];
    $hour = Chrono::parse('1 hour', '2012-08-10 12:14:00', ['forwardDate' => true])[0];

    expect($month->text)->toBe('2 months')
        ->and($month->start->date()->toDateTimeString())->toBe('2016-12-01 14:52:00')
        ->and($hour->text)->toBe('1 hour')
        ->and($hour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and(Chrono::parse('15 hours 29 min', '2012-08-10 22:30:00'))->toBe([])
        ->and(Chrono::parse('the second half', '2012-08-10 22:30:00', ['forwardDate' => true]))->toBe([]);
});

it('parses this last and next unit expressions', function () {
    $nextMonth = Chrono::parse('next month', '2016-10-01 12:00:00')[0];

    expect(Chrono::parseDate('this week', '2017-11-19 12:00:00')?->toDateTimeString())
        ->toBe('2017-11-19 12:00:00')
        ->and(Chrono::parseDate('this month', '2017-11-19 12:00:00')?->toDateTimeString())
        ->toBe('2017-11-01 12:00:00')
        ->and(Chrono::parseDate('this month', '2017-11-01 12:00:00')?->toDateTimeString())
        ->toBe('2017-11-01 12:00:00')
        ->and(Chrono::parseDate('this year', '2017-11-19 12:00:00')?->toDateTimeString())
        ->toBe('2017-01-01 12:00:00')
        ->and(Chrono::parseDate('last week', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-09-24 12:00:00')
        ->and(Chrono::parseDate('lastmonth', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-09-01 12:00:00')
        ->and(Chrono::parseDate('last day', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-09-30 12:00:00')
        ->and(Chrono::parseDate('last month', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-09-01 12:00:00')
        ->and(Chrono::parseDate('past week', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-09-24 12:00:00')
        ->and(Chrono::parseDate('next hour', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 13:00:00')
        ->and(Chrono::parseDate('next week', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-10-08 12:00:00')
        ->and(Chrono::parseDate('next day', '2016-10-01 12:00:00')?->toDateTimeString())
        ->toBe('2016-10-02 12:00:00')
        ->and($nextMonth->text)->toBe('next month')
        ->and($nextMonth->start->date()->toDateTimeString())->toBe('2016-11-01 12:00:00')
        ->and($nextMonth->start->isCertain('year'))->toBeTrue()
        ->and($nextMonth->start->isCertain('month'))->toBeTrue()
        ->and($nextMonth->start->isCertain('day'))->toBeFalse()
        ->and($nextMonth->start->isCertain('hour'))->toBeFalse();
});

it('marks relative unit certainty like chrono', function () {
    $nextHour = Chrono::parse('next hour', '2016-10-07 12:00:00')[0];
    $nextMonth = Chrono::parse('next month', '2016-10-07 12:00:00')[0];
    $nextYear = Chrono::parse('next year', '2020-11-22 12:11:32.006')[0];
    $nextQuarter = Chrono::parse('next quarter', '2021-01-22 12:00:00')[0];
    $nextQuarterAbbr = Chrono::parse('next qtr', '2021-10-22 12:00:00')[0];
    $nextTwoQuarters = Chrono::parse('next two quarter', '2021-01-22 12:00:00')[0];
    $afterThisYear = Chrono::parse('after this year', '2020-11-22 12:11:32.006')[0];
    $prefixedAfterThisYear = Chrono::parse('Connect back after this year', '2022-04-16 12:00:00')[0];

    expect($nextHour->start->date()->toDateTimeString())->toBe('2016-10-07 13:00:00')
        ->and($nextHour->start->isCertain('hour'))->toBeTrue()
        ->and($nextMonth->start->date()->toDateTimeString())->toBe('2016-11-07 12:00:00')
        ->and($nextMonth->start->isCertain('year'))->toBeTrue()
        ->and($nextMonth->start->isCertain('month'))->toBeTrue()
        ->and($nextMonth->start->isCertain('day'))->toBeFalse()
        ->and($nextYear->start->date()->format('Y-m-d H:i:s.v'))->toBe('2021-11-22 12:11:32.006')
        ->and($nextYear->start->isCertain('year'))->toBeTrue()
        ->and($nextYear->start->isCertain('month'))->toBeFalse()
        ->and($nextYear->start->isCertain('day'))->toBeFalse()
        ->and($nextYear->start->get('millisecond'))->toBe(6)
        ->and($nextYear->start->isCertain('millisecond'))->toBeFalse()
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2021-04-22 12:00:00')
        ->and($nextQuarter->start->isCertain('year'))->toBeFalse()
        ->and($nextQuarter->start->isCertain('month'))->toBeFalse()
        ->and($nextQuarter->start->isCertain('day'))->toBeFalse()
        ->and($nextQuarter->start->isCertain('hour'))->toBeFalse()
        ->and($nextQuarterAbbr->text)->toBe('next qtr')
        ->and($nextQuarterAbbr->start->date()->toDateTimeString())->toBe('2022-01-22 12:00:00')
        ->and($nextTwoQuarters->text)->toBe('next two quarter')
        ->and($nextTwoQuarters->start->date()->toDateTimeString())->toBe('2021-07-22 12:00:00')
        ->and($nextTwoQuarters->start->isCertain('year'))->toBeFalse()
        ->and($nextTwoQuarters->start->isCertain('month'))->toBeFalse()
        ->and($nextTwoQuarters->start->isCertain('day'))->toBeFalse()
        ->and($nextTwoQuarters->start->isCertain('hour'))->toBeFalse()
        ->and($afterThisYear->text)->toBe('after this year')
        ->and($afterThisYear->start->date()->format('Y-m-d H:i:s.v'))->toBe('2021-11-22 12:11:32.006')
        ->and($afterThisYear->start->isCertain('year'))->toBeTrue()
        ->and($afterThisYear->start->isCertain('month'))->toBeFalse()
        ->and($afterThisYear->start->isCertain('day'))->toBeFalse()
        ->and($afterThisYear->start->isCertain('hour'))->toBeFalse()
        ->and($prefixedAfterThisYear->text)->toBe('after this year')
        ->and($prefixedAfterThisYear->start->date()->toDateTimeString())->toBe('2023-04-16 12:00:00');
});

it('parses relative date times when the reference timezone is known', function () {
    $reference = 'Sun Nov 29 2020 13:24:13 GMT+0900 (Japan Standard Time)';
    $jst = Chrono::parse('tomorrow at 5pm', ['instant' => $reference, 'timezone' => 'JST'])[0];
    $bst = Chrono::parse('tomorrow at 5pm', ['instant' => $reference, 'timezone' => 'BST'])[0];
    $pst = Chrono::parse('tomorrow at 5pm', ['instant' => $reference, 'timezone' => -420])[0];

    expect($jst->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-11-30 17:00:00 +09:00')
        ->and($bst->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-11-30 17:00:00 +01:00')
        ->and($pst->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-11-29 17:00:00 -07:00');

    $jst->start->imply('timezoneOffset', 60);
    $pst->start->imply('timezoneOffset', 540);

    expect($jst->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-11-30 17:00:00 +01:00')
        ->and($pst->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-11-29 17:00:00 +09:00');
});

it('parses relative date times across weeks with known timezone references', function () {
    $reference = ['instant' => 'Thu Feb 27 2025 09:00:00 GMT-0800 (PST)', 'timezone' => 'PST'];

    expect(Chrono::parse('tomorrow at 9am', $reference)[0]->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2025-02-28 09:00:00 -08:00')
        ->and(Chrono::parse('in 2 weeks at 9am', $reference)[0]->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2025-03-13 09:00:00 -08:00')
        ->and(Chrono::parse('2 weeks ago at 9am', $reference)[0]->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2025-02-13 09:00:00 -08:00')
        ->and(Chrono::parse('next friday at 9am', $reference)[0]->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2025-03-07 09:00:00 -08:00');
});
