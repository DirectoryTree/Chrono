<?php

use Chrono\Chrono;

it('parses within and in relative expressions', function () {
    expect(Chrono::parseDate('we have to make something in five days.', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-28 09:15:30')
        ->and(Chrono::parseDate('within half an hour', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-23 09:45:30');
});

it('parses within expressions with upstream text and certainty semantics', function () {
    $days = Chrono::parse('we have to make something in 5 days.', '2012-08-10')[0];
    $minutes = Chrono::parse('wait for 5 minutes', '2012-08-10 12:14')[0];
    $seconds = Chrono::parse('In 5 seconds A car need to move', '2012-08-10 12:14')[0];
    $weeks = Chrono::parse('within two weeks', '2012-08-10 12:14')[0];
    $month = Chrono::parse('within a month', '2012-08-10 12:14')[0];
    $year = Chrono::parse('within one Year', '2012-08-10 12:14')[0];
    $fuzzy = Chrono::parse('In about ~5 hours', '2016-10-01 13:00')[0];
    $repeated = Chrono::parse('set a timer for 5 minutes, 30 seconds', '2012-08-10 12:14')[0];
    $repeatedWithAnd = Chrono::parse('set a timer for 1 hour, 5 minutes, and 30 seconds', '2012-08-10 12:14')[0];
    $daySized = Chrono::parse('in one day', '2020-07-10 12:14')[0];
    $hourSized = Chrono::parse('in 24 hours', '2020-07-10 12:14')[0];

    expect($days->index)->toBe(26)
        ->and($days->text)->toBe('in 5 days')
        ->and($days->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($days->tags())->toContain('result/relativeDate')
        ->and($minutes->index)->toBe(5)
        ->and($minutes->text)->toBe('for 5 minutes')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($minutes->tags())->toContain('result/relativeDateAndTime')
        ->and($seconds->text)->toBe('In 5 seconds')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($weeks->text)->toBe('within two weeks')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($weeks->start->isCertain('day'))->toBeTrue()
        ->and($weeks->start->isCertain('hour'))->toBeFalse()
        ->and($month->text)->toBe('within a month')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-09-10 12:14:00')
        ->and($month->start->isCertain('month'))->toBeTrue()
        ->and($month->start->isCertain('day'))->toBeFalse()
        ->and($year->text)->toBe('within one Year')
        ->and($year->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($year->start->isCertain('year'))->toBeTrue()
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($fuzzy->text)->toBe('In about ~5 hours')
        ->and($fuzzy->start->date()->toDateTimeString())->toBe('2016-10-01 18:00:00')
        ->and($repeated->index)->toBe(12)
        ->and($repeated->text)->toBe('for 5 minutes, 30 seconds')
        ->and($repeated->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:30')
        ->and($repeatedWithAnd->text)->toBe('for 1 hour, 5 minutes, and 30 seconds')
        ->and($repeatedWithAnd->start->date()->toDateTimeString())->toBe('2012-08-10 13:19:30')
        ->and($daySized->start->date()->toDateTimeString())->toBe('2020-07-11 12:14:00')
        ->and($daySized->start->isCertain('hour'))->toBeFalse()
        ->and($hourSized->start->date()->toDateTimeString())->toBe('2020-07-11 12:14:00')
        ->and($hourSized->start->isCertain('hour'))->toBeTrue()
        ->and(Chrono::parse('for the unit tests', '2012-08-10 12:14'))->toBe([]);
});

it('parses upstream within variants with exact text and references', function () {
    $tenDays = Chrono::parse('we have to make something within 10 day', '2012-08-10 12:14')[0];
    $fiveMinutes = Chrono::parse('in 5 minutes', '2012-08-10 12:14')[0];
    $oneHour = Chrono::parse('within 1 hour', '2012-08-10 12:14')[0];
    $sentenceMinutes = Chrono::parse('In 5 minutes I will go home', '2012-08-10 12:14')[0];
    $sentenceTitleMinutes = Chrono::parse('In 5 Minutes A car need to move', '2012-08-10 12:14')[0];
    $sentenceShortMinutes = Chrono::parse('In 5 mins a car need to move', '2012-08-10 12:14')[0];
    $fewMonths = Chrono::parse('within a few months', '2012-07-10 12:14')[0];
    $lowerYear = Chrono::parse('within one year', '2012-08-10 12:14')[0];
    $titleYear = Chrono::parse('within One year', '2012-08-10 12:14')[0];
    $week = Chrono::parse('in a week', '2016-10-01')[0];
    $aroundHours = Chrono::parse('In around 5 hours', '2016-10-01 13:00')[0];
    $aboutHours = Chrono::parse('In  about 5 hours', '2012-08-10 12:49')[0];
    $withinAroundHours = Chrono::parse('within around 3 hours', '2012-08-10 12:49')[0];
    $oneMonth = Chrono::parse('in 1 month', '2016-10-01 14:52')[0];

    expect($tenDays->index)->toBe(26)
        ->and($tenDays->text)->toBe('within 10 day')
        ->and($tenDays->start->date()->toDateTimeString())->toBe('2012-08-20 12:14:00')
        ->and($fiveMinutes->text)->toBe('in 5 minutes')
        ->and($fiveMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($oneHour->text)->toBe('within 1 hour')
        ->and($oneHour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($sentenceMinutes->text)->toBe('In 5 minutes')
        ->and($sentenceMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($sentenceTitleMinutes->text)->toBe('In 5 Minutes')
        ->and($sentenceTitleMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($sentenceShortMinutes->text)->toBe('In 5 mins')
        ->and($sentenceShortMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($fewMonths->text)->toBe('within a few months')
        ->and($fewMonths->start->date()->toDateTimeString())->toBe('2012-10-10 12:14:00')
        ->and($lowerYear->text)->toBe('within one year')
        ->and($lowerYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($titleYear->text)->toBe('within One year')
        ->and($titleYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($week->text)->toBe('in a week')
        ->and($week->start->date()->toDateTimeString())->toBe('2016-10-08 00:00:00')
        ->and($aroundHours->text)->toBe('In around 5 hours')
        ->and($aroundHours->start->date()->toDateTimeString())->toBe('2016-10-01 18:00:00')
        ->and($aboutHours->text)->toBe('In  about 5 hours')
        ->and($aboutHours->start->date()->toDateTimeString())->toBe('2012-08-10 17:49:00')
        ->and($withinAroundHours->text)->toBe('within around 3 hours')
        ->and($withinAroundHours->start->date()->toDateTimeString())->toBe('2012-08-10 15:49:00')
        ->and($oneMonth->text)->toBe('in 1 month')
        ->and($oneMonth->start->date()->toDateTimeString())->toBe('2016-11-01 14:52:00');
});

it('parses upstream within repeated time unit variants', function () {
    $spaceSeparated = Chrono::parse('for 5 minutes 30 seconds', '2012-08-10 12:14')[0];
    $commaSeparated = Chrono::parse('for 1 hour, 5 minutes, 30 seconds', '2012-08-10 12:14')[0];
    $andSeparated = Chrono::parse('for 5 minutes and 30 seconds', '2012-08-10 12:14')[0];

    expect($spaceSeparated->text)->toBe('for 5 minutes 30 seconds')
        ->and($spaceSeparated->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:30')
        ->and($commaSeparated->text)->toBe('for 1 hour, 5 minutes, 30 seconds')
        ->and($commaSeparated->start->date()->toDateTimeString())->toBe('2012-08-10 13:19:30')
        ->and($andSeparated->text)->toBe('for 5 minutes and 30 seconds')
        ->and($andSeparated->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:30');
});

it('marks upstream within time unit certainty', function () {
    $minutes = Chrono::parse('in 2 minute', '2016-10-01 14:52')[0];
    $attachedHours = Chrono::parse('in 2hour', '2016-10-01 14:52')[0];
    $fewYears = Chrono::parse('in a few year', '2016-10-01 14:52')[0];
    $months = Chrono::parse('within 12 month', '2016-10-01 14:52')[0];
    $days = Chrono::parse('within 3 days', '2016-10-01 14:52')[0];

    expect($minutes->start->date()->toDateTimeString())->toBe('2016-10-01 14:54:00')
        ->and($minutes->start->isCertain('year'))->toBeTrue()
        ->and($minutes->start->isCertain('month'))->toBeTrue()
        ->and($minutes->start->isCertain('day'))->toBeTrue()
        ->and($minutes->start->isCertain('hour'))->toBeTrue()
        ->and($minutes->start->isCertain('minute'))->toBeTrue()
        ->and($attachedHours->start->date()->toDateTimeString())->toBe('2016-10-01 16:52:00')
        ->and($attachedHours->start->isCertain('year'))->toBeTrue()
        ->and($attachedHours->start->isCertain('month'))->toBeTrue()
        ->and($attachedHours->start->isCertain('day'))->toBeTrue()
        ->and($attachedHours->start->isCertain('hour'))->toBeTrue()
        ->and($attachedHours->start->isCertain('minute'))->toBeTrue()
        ->and($fewYears->start->date()->toDateTimeString())->toBe('2019-10-01 14:52:00')
        ->and($fewYears->start->isCertain('year'))->toBeTrue()
        ->and($fewYears->start->isCertain('month'))->toBeFalse()
        ->and($fewYears->start->isCertain('day'))->toBeFalse()
        ->and($fewYears->start->isCertain('hour'))->toBeFalse()
        ->and($fewYears->start->isCertain('minute'))->toBeFalse()
        ->and($months->start->date()->toDateTimeString())->toBe('2017-10-01 14:52:00')
        ->and($months->start->isCertain('year'))->toBeTrue()
        ->and($months->start->isCertain('month'))->toBeTrue()
        ->and($months->start->isCertain('day'))->toBeFalse()
        ->and($months->start->isCertain('hour'))->toBeFalse()
        ->and($months->start->isCertain('minute'))->toBeFalse()
        ->and($days->start->date()->toDateTimeString())->toBe('2016-10-04 14:52:00')
        ->and($days->start->isCertain('year'))->toBeTrue()
        ->and($days->start->isCertain('month'))->toBeTrue()
        ->and($days->start->isCertain('day'))->toBeTrue()
        ->and($days->start->isCertain('hour'))->toBeFalse()
        ->and($days->start->isCertain('minute'))->toBeFalse();
});

it('parses fuzzy within amount phrases', function () {
    expect(Chrono::parseDate('within a few months', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-11-10 12:49:00')
        ->and(Chrono::parseDate('In several hours', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-08-10 19:49:00')
        ->and(Chrono::parseDate('In a couple of days', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-08-12 12:49:00');
});
