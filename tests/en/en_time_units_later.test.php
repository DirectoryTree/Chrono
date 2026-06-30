<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses later relative expressions with upstream-shaped components', function () {
    $days = Chrono::parse('2 days later', '2012-08-10 12:00')[0];
    $minutes = Chrono::parse('5 minutes later', '2012-08-10 10:00')[0];
    $weeks = Chrono::parse('3 week later', '2012-07-10 10:00')[0];
    $shortWeeks = Chrono::parse('3w later', '2012-07-10 10:00')[0];
    $shortMonths = Chrono::parse('3mo later', '2012-07-10 10:00')[0];
    $fromNow = Chrono::parse('10 days from now, we did something', '2012-08-10')[0];
    $minuteFromNow = Chrono::parse('15 minute from now', '2012-08-10 12:14')[0];
    $earlier = Chrono::parse('15 minutes earlier', '2012-08-10 12:14')[0];
    $out = Chrono::parse('15 minute out', '2012-08-10 12:14')[0];
    $spacedHours = Chrono::parse('   12 hours from now', '2012-08-10 12:14')[0];
    $spacedShortHours = Chrono::parse('   12 hrs from now', '2012-08-10 12:14')[0];
    $halfHour = Chrono::parse('   half an hour from now', '2012-08-10 12:14')[0];
    $seconds = Chrono::parse('12 seconds from now I did something', '2012-08-10 12:14')[0];
    $spelledSeconds = Chrono::parse('three seconds from now I did something', '2012-08-10 12:14')[0];
    $capitalDays = Chrono::parse('5 Days from now, we did something', '2012-08-10')[0];
    $capitalHalfHour = Chrono::parse('   half An hour from now', '2012-08-10 12:14')[0];
    $singleDay = Chrono::parse('A days from now, we did something', '2012-08-10')[0];
    $minuteOut = Chrono::parse('a min out', '2012-08-10 12:14')[0];
    $minuteAfter = Chrono::parse('the min after', '2012-08-10 12:14')[0];
    $inHour = Chrono::parse('in 1 hour', '2012-08-10 12:14')[0];
    $inMonth = Chrono::parse('in 1 mon', '2012-08-10 12:14')[0];
    $decimalHours = Chrono::parse('in 1.5 hours', '2012-08-10 12:40')[0];
    $multiple = Chrono::parse('in 1d 2hr 5min', '2012-08-10 12:40')[0];
    $multipleWithAnd = Chrono::parse('in 1d, 2hr, and 5min', '2012-08-10 12:40')[0];

    expect($days->index)->toBe(0)
        ->and($days->text)->toBe('2 days later')
        ->and($days->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($days->tags())->toContain('result/relativeDate')
        ->and($days->start->isCertain('day'))->toBeTrue()
        ->and($days->start->isCertain('month'))->toBeTrue()
        ->and($minutes->text)->toBe('5 minutes later')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2012-08-10 10:05:00')
        ->and($minutes->start->isCertain('hour'))->toBeTrue()
        ->and($minutes->start->isCertain('minute'))->toBeTrue()
        ->and($weeks->text)->toBe('3 week later')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-07-31 10:00:00')
        ->and($shortWeeks->text)->toBe('3w later')
        ->and($shortWeeks->start->date()->toDateTimeString())->toBe('2012-07-31 10:00:00')
        ->and($shortMonths->text)->toBe('3mo later')
        ->and($shortMonths->start->date()->toDateTimeString())->toBe('2012-10-10 10:00:00')
        ->and($fromNow->text)->toBe('10 days from now')
        ->and($fromNow->start->date()->toDateTimeString())->toBe('2012-08-20 00:00:00')
        ->and($minuteFromNow->text)->toBe('15 minute from now')
        ->and($minuteFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($minuteFromNow->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($earlier->text)->toBe('15 minutes earlier')
        ->and($earlier->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($earlier->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($out->text)->toBe('15 minute out')
        ->and($out->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($spacedHours->index)->toBe(3)
        ->and($spacedHours->text)->toBe('12 hours from now')
        ->and($spacedHours->start->date()->toDateTimeString())->toBe('2012-08-11 00:14:00')
        ->and($spacedShortHours->index)->toBe(3)
        ->and($spacedShortHours->text)->toBe('12 hrs from now')
        ->and($spacedShortHours->start->date()->toDateTimeString())->toBe('2012-08-11 00:14:00')
        ->and($spacedShortHours->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($halfHour->index)->toBe(3)
        ->and($halfHour->text)->toBe('half an hour from now')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($seconds->text)->toBe('12 seconds from now')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:12')
        ->and($spelledSeconds->text)->toBe('three seconds from now')
        ->and($spelledSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:03')
        ->and($capitalDays->text)->toBe('5 Days from now')
        ->and($capitalDays->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($capitalHalfHour->index)->toBe(3)
        ->and($capitalHalfHour->text)->toBe('half An hour from now')
        ->and($capitalHalfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($singleDay->text)->toBe('A days from now')
        ->and($singleDay->start->date()->toDateTimeString())->toBe('2012-08-11 00:00:00')
        ->and($minuteOut->text)->toBe('a min out')
        ->and($minuteOut->start->date()->toDateTimeString())->toBe('2012-08-10 12:15:00')
        ->and($minuteAfter->text)->toBe('the min after')
        ->and($minuteAfter->start->date()->toDateTimeString())->toBe('2012-08-10 12:15:00')
        ->and($inHour->text)->toBe('in 1 hour')
        ->and($inHour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($inMonth->text)->toBe('in 1 mon')
        ->and($inMonth->start->date()->toDateTimeString())->toBe('2012-09-10 12:14:00')
        ->and($decimalHours->text)->toBe('in 1.5 hours')
        ->and($decimalHours->start->date()->toDateTimeString())->toBe('2012-08-10 14:10:00')
        ->and($multiple->text)->toBe('in 1d 2hr 5min')
        ->and($multiple->start->date()->toDateTimeString())->toBe('2012-08-11 14:45:00')
        ->and($multipleWithAnd->text)->toBe('in 1d, 2hr, and 5min')
        ->and($multipleWithAnd->start->date()->toDateTimeString())->toBe('2012-08-11 14:45:00');
});

it('parses later relative expressions in strict mode like upstream', function () {
    $fromNow = Chrono::strict()->parseText('15 minutes from now', '2012-08-10 12:14')[0];
    $later = Chrono::strict()->parseText('25 minutes later', '2012-08-10 12:40')[0];

    expect($fromNow->text)->toBe('15 minutes from now')
        ->and($fromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($later->text)->toBe('25 minutes later')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-10 13:05:00')
        ->and(Chrono::strict()->parseText('15m from now', '2012-08-10 12:14'))->toBe([])
        ->and(Chrono::strict()->parseText('15s later', '2012-08-10 12:14'))->toBe([]);
});

it('merges later durations before and after parsed references', function () {
    $today = Chrono::parse('2 day after today', '2012-08-10')[0];
    $tomorrow = Chrono::parse('the day after tomorrow', '2012-08-10')[0];
    $twoDaysAfterTomorrow = Chrono::parse('2 day after tomorrow', '2012-08-10')[0];
    $weekAfterTomorrow = Chrono::parse('a week after tomorrow', '2012-08-10')[0];
    $weekdayOffset = Chrono::parse('next tuesday +10 days', '2023-12-29')[0];
    $dateOffset = Chrono::parse('2023-12-29 -10days', '2023-12-29')[0];
    $nowOffset = Chrono::parse('now + 40minutes', '2023-12-29 08:30')[0];

    expect($today->text)->toBe('2 day after today')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($today->tags())->toContain('refiner/mergeRelativeFollowByDate')
        ->and($tomorrow->text)->toBe('the day after tomorrow')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($twoDaysAfterTomorrow->text)->toBe('2 day after tomorrow')
        ->and($twoDaysAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($weekAfterTomorrow->text)->toBe('a week after tomorrow')
        ->and($weekAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-18 00:00:00')
        ->and($weekdayOffset->text)->toBe('next tuesday +10 days')
        ->and($weekdayOffset->start->date()->toDateTimeString())->toBe('2024-01-12 12:00:00')
        ->and($weekdayOffset->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and($dateOffset->text)->toBe('2023-12-29 -10days')
        ->and($dateOffset->start->date()->toDateTimeString())->toBe('2023-12-19 12:00:00')
        ->and($nowOffset->text)->toBe('now + 40minutes')
        ->and($nowOffset->start->date()->toDateTimeString())->toBe('2023-12-29 09:10:00')
        ->and($nowOffset->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and(Chrono::parse('tell them later', '2012-08-10'))->toBe([]);
});
