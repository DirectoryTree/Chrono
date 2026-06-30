<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses ago and before relative durations like upstream', function () {
    $fiveDays = Chrono::parse('5 days ago, we did something', '2012-08-10')[0];
    $tenDays = Chrono::parse('10 days ago, we did something', '2012-08-10')[0];
    $minuteAgo = Chrono::parse('15 minute ago', '2012-08-10 12:14')[0];
    $minuteEarlier = Chrono::parse('15 minute earlier', '2012-08-10 12:14')[0];
    $minuteBefore = Chrono::parse('15 minute before', '2012-08-10 12:14')[0];
    $spacedHours = Chrono::parse('   12 hours ago', '2012-08-10 12:14')[0];
    $shortHour = Chrono::parse('1h ago', '2012-08-10 12:14')[0];
    $shortHourLong = Chrono::parse('1hr ago', '2012-08-10 12:14')[0];
    $halfHour = Chrono::parse('   half an hour ago', '2012-08-10 12:14')[0];
    $sentenceHours = Chrono::parse('12 hours ago I did something', '2012-08-10 12:14')[0];
    $seconds = Chrono::parse('12 seconds ago I did something', '2012-08-10 12:14')[0];
    $spelledSeconds = Chrono::parse('three seconds ago I did something', '2012-08-10 12:14')[0];
    $capitalDays = Chrono::parse('5 Days ago, we did something', '2012-08-10')[0];
    $capitalHalfHour = Chrono::parse('   half An hour ago', '2012-08-10 12:14')[0];
    $singleDay = Chrono::parse('A days ago, we did something', '2012-08-10')[0];
    $minuteBeforeArticle = Chrono::parse('a min before', '2012-08-10 12:14')[0];
    $minuteBeforeAlias = Chrono::parse('the min before', '2012-08-10 12:14')[0];
    $months = Chrono::parse('5 months ago, we did something', '2012-10-10')[0];
    $years = Chrono::parse('5 years ago, we did something', '2012-08-10')[0];
    $week = Chrono::parse('a week ago, we did something', '2012-08-03')[0];
    $fewDays = Chrono::parse('a few days ago, we did something', '2012-08-03')[0];
    $nested = Chrono::parse('15 hours 29 min ago', '2012-08-10 22:30')[0];
    $nestedDayHours = Chrono::parse('1 day 21 hours ago ', '2012-08-10 22:30')[0];
    $nestedCompact = Chrono::parse('1d 21 h 25m ago ', '2012-08-10 22:30')[0];
    $nestedSeconds = Chrono::parse('3 min 49 sec ago ', '2012-08-10 22:30')[0];
    $nestedCompactSeconds = Chrono::parse('3m 49s ago ', '2012-08-10 22:30')[0];

    expect($fiveDays->index)->toBe(0)
        ->and($fiveDays->text)->toBe('5 days ago')
        ->and($fiveDays->start->date()->toDateTimeString())->toBe('2012-08-05 00:00:00')
        ->and($fiveDays->tags())->toContain('result/relativeDate')
        ->and($tenDays->text)->toBe('10 days ago')
        ->and($tenDays->start->date()->toDateTimeString())->toBe('2012-07-31 00:00:00')
        ->and($minuteAgo->text)->toBe('15 minute ago')
        ->and($minuteAgo->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($minuteAgo->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($minuteAgo->tags())->toContain('result/relativeDateAndTime')
        ->and($minuteEarlier->text)->toBe('15 minute earlier')
        ->and($minuteEarlier->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($minuteBefore->text)->toBe('15 minute before')
        ->and($minuteBefore->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($spacedHours->index)->toBe(3)
        ->and($spacedHours->text)->toBe('12 hours ago')
        ->and($spacedHours->start->date()->toDateTimeString())->toBe('2012-08-10 00:14:00')
        ->and($shortHour->text)->toBe('1h ago')
        ->and($shortHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:14:00')
        ->and($shortHourLong->text)->toBe('1hr ago')
        ->and($shortHourLong->start->date()->toDateTimeString())->toBe('2012-08-10 11:14:00')
        ->and($halfHour->index)->toBe(3)
        ->and($halfHour->text)->toBe('half an hour ago')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($sentenceHours->text)->toBe('12 hours ago')
        ->and($sentenceHours->start->date()->toDateTimeString())->toBe('2012-08-10 00:14:00')
        ->and($seconds->text)->toBe('12 seconds ago')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:48')
        ->and($seconds->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($spelledSeconds->text)->toBe('three seconds ago')
        ->and($spelledSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:57')
        ->and($capitalDays->text)->toBe('5 Days ago')
        ->and($capitalDays->start->date()->toDateTimeString())->toBe('2012-08-05 00:00:00')
        ->and($capitalHalfHour->index)->toBe(3)
        ->and($capitalHalfHour->text)->toBe('half An hour ago')
        ->and($capitalHalfHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($singleDay->text)->toBe('A days ago')
        ->and($singleDay->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($minuteBeforeArticle->text)->toBe('a min before')
        ->and($minuteBeforeArticle->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:00')
        ->and($minuteBeforeAlias->text)->toBe('the min before')
        ->and($minuteBeforeAlias->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:00')
        ->and($minuteBeforeAlias->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($months->text)->toBe('5 months ago')
        ->and($months->start->date()->toDateTimeString())->toBe('2012-05-10 00:00:00')
        ->and($years->text)->toBe('5 years ago')
        ->and($years->start->date()->toDateTimeString())->toBe('2007-08-10 00:00:00')
        ->and($week->text)->toBe('a week ago')
        ->and($week->start->date()->toDateTimeString())->toBe('2012-07-27 00:00:00')
        ->and($fewDays->text)->toBe('a few days ago')
        ->and($fewDays->start->date()->toDateTimeString())->toBe('2012-07-31 00:00:00')
        ->and($nested->text)->toBe('15 hours 29 min ago')
        ->and($nested->start->date()->toDateTimeString())->toBe('2012-08-10 07:01:00')
        ->and($nestedDayHours->text)->toBe('1 day 21 hours ago')
        ->and($nestedDayHours->start->date()->toDateTimeString())->toBe('2012-08-09 01:30:00')
        ->and($nestedCompact->text)->toBe('1d 21 h 25m ago')
        ->and($nestedCompact->start->date()->toDateTimeString())->toBe('2012-08-09 01:05:00')
        ->and($nestedSeconds->text)->toBe('3 min 49 sec ago')
        ->and($nestedSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 22:26:11')
        ->and($nestedCompactSeconds->text)->toBe('3m 49s ago')
        ->and($nestedCompactSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 22:26:11')
        ->and(Chrono::parseDate('2 days ago', '2024-09-10 12:00', ['forwardDate' => true])?->toDateTimeString())->toBe('2024-09-08 12:00:00')
        ->and(Chrono::parseDate('2 weeks ago', '2024-09-10 12:00', ['forwardDate' => true])?->toDateTimeString())->toBe('2024-08-27 12:00:00')
        ->and(Chrono::parseDate('2 months ago', '2024-09-10 12:00', ['forwardDate' => true])?->toDateTimeString())->toBe('2024-07-10 12:00:00')
        ->and(Chrono::parseDate('2 years ago', '2024-09-10 12:00', ['forwardDate' => true])?->toDateTimeString())->toBe('2022-09-10 12:00:00')
        ->and(Chrono::strict()->parseDateText('5 minutes ago', '2012-08-10 12:14')?->toDateTimeString())->toBe('2012-08-10 12:09:00')
        ->and(Chrono::strict()->parseText('5m ago', '2012-08-10 12:14'))->toBe([])
        ->and(Chrono::strict()->parseText('5hr before', '2012-08-10 12:14'))->toBe([])
        ->and(Chrono::strict()->parseText('5 h ago', '2012-08-10 12:14'))->toBe([])
        ->and(Chrono::parse('15 hours 29 min', '2012-08-10 22:30'))->toBe([])
        ->and(Chrono::parse('a few hour', '2012-08-10 22:30'))->toBe([])
        ->and(Chrono::parse('5 days', '2012-08-10 22:30'))->toBe([])
        ->and(Chrono::parse('am ago', '2012-08-10 22:30'))->toBe([])
        ->and(Chrono::parse('them ago', '2012-08-10 22:30'))->toBe([]);
});

it('parses upstream ago relative duration variants with exact text', function () {
    $fiveDays = Chrono::parse('5 days ago, we did something', '2012-08-10 12:00')[0];
    $fifteenMinutes = Chrono::parse('15 minute ago', '2012-08-10 12:14')[0];
    $halfHour = Chrono::parse('   half An hour ago', '2012-08-10 12:14')[0];
    $shortHour = Chrono::parse('1hr ago', '2012-08-10 12:14')[0];
    $nested = Chrono::parse('1d 21 h 25m ago ', '2012-08-10 22:30')[0];
    $articleDay = Chrono::parse('A days ago, we did something', '2012-08-10 12:00')[0];

    expect($fiveDays->text)->toBe('5 days ago')
        ->and($fiveDays->index)->toBe(0)
        ->and($fiveDays->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($fiveDays->tags())->toContain('result/relativeDate')
        ->and($fifteenMinutes->text)->toBe('15 minute ago')
        ->and($fifteenMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($fifteenMinutes->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($fifteenMinutes->tags())->toContain('result/relativeDateAndTime')
        ->and($halfHour->index)->toBe(3)
        ->and($halfHour->text)->toBe('half An hour ago')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($shortHour->text)->toBe('1hr ago')
        ->and($shortHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:14:00')
        ->and($nested->text)->toBe('1d 21 h 25m ago')
        ->and($nested->start->date()->toDateTimeString())->toBe('2012-08-09 01:05:00')
        ->and($articleDay->text)->toBe('A days ago')
        ->and($articleDay->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00');
});
