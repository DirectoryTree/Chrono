<?php

use Chrono\Chrono;
use Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;

it('parses signed relative durations', function () {
    $plusMinutes = Chrono::casual()->parseText('+15 minutes', '2012-07-10 12:14')[0];
    $plusShortMinute = Chrono::casual()->parseText('+1m', '2012-07-10 12:14')[0];

    expect($plusMinutes->text)->toBe('+15 minutes')
        ->and($plusMinutes->start->date()->toDateTimeString())->toBe('2012-07-10 12:29:00')
        ->and(Chrono::parseDate('+15min', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($plusShortMinute->text)->toBe('+1m')
        ->and($plusShortMinute->start->date()->toDateTimeString())->toBe('2012-07-10 12:15:00')
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

it('parses casual positive relative duration prefixes', function () {
    $nextWeeks = Chrono::parse('next 2 weeks', '2016-10-01 12:00')[0];
    $nextDays = Chrono::parse('next 2 days', '2016-10-01 12:00')[0];
    $nextYears = Chrono::parse('next two years', '2016-10-01 12:00')[0];
    $compound = Chrono::parse('next 2 weeks 3 days', '2016-10-01 12:00')[0];
    $afterYear = Chrono::parse('after a year', '2016-10-01 12:00')[0];
    $afterHour = Chrono::parse('after an hour', '2016-10-01 15:00')[0];

    expect($nextWeeks->text)->toBe('next 2 weeks')
        ->and($nextWeeks->tags())->toContain('result/relativeDate')
        ->and($nextWeeks->start->date()->toDateTimeString())->toBe('2016-10-15 12:00:00')
        ->and($nextDays->text)->toBe('next 2 days')
        ->and($nextDays->start->date()->toDateTimeString())->toBe('2016-10-03 12:00:00')
        ->and($nextYears->text)->toBe('next two years')
        ->and($nextYears->start->date()->toDateTimeString())->toBe('2018-10-01 12:00:00')
        ->and($compound->text)->toBe('next 2 weeks 3 days')
        ->and($compound->start->date()->toDateTimeString())->toBe('2016-10-18 12:00:00')
        ->and($afterYear->text)->toBe('after a year')
        ->and($afterYear->start->date()->toDateTimeString())->toBe('2017-10-01 12:00:00')
        ->and($afterHour->text)->toBe('after an hour')
        ->and($afterHour->tags())->toContain('result/relativeDate')
        ->and($afterHour->tags())->toContain('result/relativeDateAndTime')
        ->and($afterHour->start->date()->toDateTimeString())->toBe('2016-10-01 16:00:00');
});

it('parses casual negative relative duration prefixes', function () {
    $lastWeeks = Chrono::parse('last 2 weeks', '2016-10-01 12:00')[0];
    $lastWeeksText = Chrono::parse('last two weeks', '2016-10-01 12:00')[0];
    $pastDays = Chrono::parse('past 2 days', '2016-10-01 12:00')[0];
    $plusCompound = Chrono::parse('+2 months, 5 days', '2016-10-01 12:00')[0];

    expect($lastWeeks->text)->toBe('last 2 weeks')
        ->and($lastWeeks->start->date()->toDateTimeString())->toBe('2016-09-17 12:00:00')
        ->and($lastWeeksText->text)->toBe('last two weeks')
        ->and($lastWeeksText->start->date()->toDateTimeString())->toBe('2016-09-17 12:00:00')
        ->and($pastDays->text)->toBe('past 2 days')
        ->and($pastDays->start->date()->toDateTimeString())->toBe('2016-09-29 12:00:00')
        ->and($plusCompound->text)->toBe('+2 months, 5 days')
        ->and($plusCompound->start->date()->toDateTimeString())->toBe('2016-12-06 12:00:00');
});

it('rejects casual relative duration false positives', function () {
    expect(Chrono::casual()->parseText('3y', '2015-07-10 12:14'))->toBe([])
        ->and(Chrono::casual()->parseText('1 m', '2015-07-10 12:14'))->toBe([])
        ->and(Chrono::casual()->parseText('the day', '2015-07-10 12:14'))->toBe([])
        ->and(Chrono::casual()->parseText('a day', '2015-07-10 12:14'))->toBe([])
        ->and(Chrono::parse('+am'))->toBe([])
        ->and(Chrono::parse('+them'))->toBe([]);
});

it('can disable casual relative duration abbreviations', function () {
    $custom = Chrono::strict()->withParser(new EnTimeUnitCasualRelativeFormatParser(false));
    $result = $custom->parseText('-2 hours 5 minutes', '2016-10-01 12:00')[0];

    expect($custom->parseText('-3y'))->toBe([])
        ->and($custom->parseText('last 2m'))->toBe([])
        ->and($result->text)->toBe('-2 hours 5 minutes')
        ->and($result->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00');
});
