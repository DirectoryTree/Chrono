<?php

use Chrono\Chrono;

it('parses ukrainian weekdays times and relative durations', function () {
    $weekday = Chrono::uk()->parseText('середа', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::uk()->parseText('наступний понеділок', '2012-08-10 09:30')[0];
    $time = Chrono::uk()->parseText('о 6:30 вечора', '2012-08-10 09:30')[0];
    $fullTime = Chrono::uk()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $timeRange = Chrono::uk()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morning = Chrono::uk()->parseText('об 11 ранку', '2016-10-01 08:00')[0];
    $evening = Chrono::uk()->parseText('в 11 вечора', '2016-10-01 08:00')[0];
    $morningRange = Chrono::uk()->parseText('з 10 до 11 ранку', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::uk()->parseText('із 10 до 11 вечора', '2016-10-01 08:00')[0];
    $casualHour = Chrono::ukrainian()->parseText('в 1', '2016-10-01 08:00')[0];
    $casualNoon = Chrono::ukrainian()->parseText('о 12', '2016-10-01 08:00')[0];
    $casualDotted = Chrono::ukrainian()->parseText('в 12.30', '2016-10-01 08:00')[0];
    $thisWeek = Chrono::uk()->parseText('на цьому тижні', '2017-11-19 12:00')[0];
    $thisMonth = Chrono::uk()->parseText('у цьому місяці', '2017-11-19 12:00')[0];
    $firstOfThisMonth = Chrono::uk()->parseText('цього місяця', '2017-11-01 12:00')[0];
    $thisYear = Chrono::uk()->parseText('у цьому році', '2017-11-19 12:00')[0];
    $pastWeek = Chrono::uk()->parseText('на минулому тижні', '2016-10-01 12:00')[0];
    $pastMonth = Chrono::uk()->parseText('минулого місяця', '2016-10-01 12:00')[0];
    $pastYear = Chrono::uk()->parseText('у минулому році', '2016-10-01 12:00')[0];
    $nextWeek = Chrono::uk()->parseText('на наступному тижні', '2016-10-01 12:00')[0];
    $nextMonth = Chrono::uk()->parseText('наступного місяця', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::uk()->parseText('в наступному кварталі', '2016-10-01 12:00')[0];
    $nextYear = Chrono::uk()->parseText('наступного року', '2016-10-01 12:00')[0];
    $ago = Chrono::uk()->parseText('2 дні тому', '2012-08-10 09:30')[0];
    $halfHour = Chrono::uk()->parseText('через півгодини', '2016-10-01 12:00')[0];
    $later = Chrono::uk()->parseText('через 3 тижні', '2012-08-10 09:30')[0];
    $within = Chrono::uk()->parseText('протягом 1 місяця', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::uk()->parseText('буде зроблено протягом хвилини', '2012-08-10 00:00')[0];
    $withinHours = Chrono::uk()->parseText('буде виконано на протязі 2 годин.', '2012-08-10 00:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/UKTimeExpressionParser')
        ->and($fullTime->text)->toBe('20:32:13')
        ->and($fullTime->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morning->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($casualHour->index)->toBe(0)
        ->and($casualHour->text)->toBe('в 1')
        ->and($casualHour->start->get('hour'))->toBe(1)
        ->and($casualNoon->index)->toBe(0)
        ->and($casualNoon->text)->toBe('о 12')
        ->and($casualNoon->start->get('hour'))->toBe(12)
        ->and($casualDotted->index)->toBe(0)
        ->and($casualDotted->text)->toBe('в 12.30')
        ->and($casualDotted->start->get('hour'))->toBe(12)
        ->and($casualDotted->start->get('minute'))->toBe(30)
        ->and($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($thisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($firstOfThisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($thisYear->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00')
        ->and($pastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($pastMonth->start->date()->toDateTimeString())->toBe('2016-09-01 12:00:00')
        ->and($pastYear->start->date()->toDateTimeString())->toBe('2015-10-01 12:00:00')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2016-10-08 12:00:00')
        ->and($nextMonth->start->date()->toDateTimeString())->toBe('2016-11-01 12:00:00')
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00')
        ->and($nextYear->start->date()->toDateTimeString())->toBe('2017-10-01 12:00:00')
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/UKTimeUnitAgoFormatParser')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2016-10-01 12:30:00')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/UKTimeUnitCasualRelativeFormatParser')
        ->and($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/UKTimeUnitWithinFormatParser')
        ->and($within->start->isCertain('month'))->toBeTrue()
        ->and($within->start->isCertain('day'))->toBeFalse()
        ->and($withinMinute->index)->toBe(26)
        ->and($withinMinute->text)->toBe('протягом хвилини')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and($withinMinute->start->isCertain('hour'))->toBeTrue()
        ->and($withinMinute->start->isCertain('minute'))->toBeTrue()
        ->and($withinMinute->start->tags())->toContain('result/relativeDateAndTime')
        ->and($withinHours->index)->toBe(26)
        ->and($withinHours->text)->toBe('на протязі 2 годин')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 02:00:00')
        ->and($withinHours->start->isCertain('hour'))->toBeTrue()
        ->and(Chrono::uk()->parseText('Температура 101,194 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 101 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 101 стіл!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('7-730', '2012-08-10'))->toBe([]);
});
