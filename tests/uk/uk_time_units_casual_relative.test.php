<?php

use DirectoryTree\Chrono\Chrono;

it('parses ukrainian weekdays times and relative durations', function () {
    $weekday = Chrono::uk()->parse('середа', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::uk()->parse('наступний понеділок', '2012-08-10 09:30')[0];
    $time = Chrono::uk()->parse('о 6:30 вечора', '2012-08-10 09:30')[0];
    $fullTime = Chrono::uk()->parse('20:32:13', '2016-10-01 08:00')[0];
    $timeRange = Chrono::uk()->parse('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morning = Chrono::uk()->parse('об 11 ранку', '2016-10-01 08:00')[0];
    $evening = Chrono::uk()->parse('в 11 вечора', '2016-10-01 08:00')[0];
    $morningRange = Chrono::uk()->parse('з 10 до 11 ранку', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::uk()->parse('із 10 до 11 вечора', '2016-10-01 08:00')[0];
    $casualHour = Chrono::ukrainian()->parse('в 1', '2016-10-01 08:00')[0];
    $casualNoon = Chrono::ukrainian()->parse('о 12', '2016-10-01 08:00')[0];
    $casualDotted = Chrono::ukrainian()->parse('в 12.30', '2016-10-01 08:00')[0];
    $thisWeek = Chrono::uk()->parse('на цьому тижні', '2017-11-19 12:00')[0];
    $thisMonth = Chrono::uk()->parse('у цьому місяці', '2017-11-19 12:00')[0];
    $firstOfThisMonth = Chrono::uk()->parse('цього місяця', '2017-11-01 12:00')[0];
    $thisYear = Chrono::uk()->parse('у цьому році', '2017-11-19 12:00')[0];
    $pastWeek = Chrono::uk()->parse('на минулому тижні', '2016-10-01 12:00')[0];
    $pastMonth = Chrono::uk()->parse('минулого місяця', '2016-10-01 12:00')[0];
    $pastYear = Chrono::uk()->parse('у минулому році', '2016-10-01 12:00')[0];
    $nextWeek = Chrono::uk()->parse('на наступному тижні', '2016-10-01 12:00')[0];
    $nextMonth = Chrono::uk()->parse('наступного місяця', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::uk()->parse('в наступному кварталі', '2016-10-01 12:00')[0];
    $nextYear = Chrono::uk()->parse('наступного року', '2016-10-01 12:00')[0];
    $ago = Chrono::uk()->parse('2 дні тому', '2012-08-10 09:30')[0];
    $halfHour = Chrono::uk()->parse('через півгодини', '2016-10-01 12:00')[0];
    $later = Chrono::uk()->parse('через 3 тижні', '2012-08-10 09:30')[0];
    $within = Chrono::uk()->parse('протягом 1 місяця', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::uk()->parse('буде зроблено протягом хвилини', '2012-08-10 00:00')[0];
    $withinHours = Chrono::uk()->parse('буде виконано на протязі 2 годин.', '2012-08-10 00:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
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
        ->and($withinMinute->index)->toBe(14)
        ->and($withinMinute->text)->toBe('протягом хвилини')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and($withinMinute->start->isCertain('hour'))->toBeTrue()
        ->and($withinMinute->start->isCertain('minute'))->toBeTrue()
        ->and($withinMinute->start->tags())->toContain('result/relativeDateAndTime')
        ->and($withinHours->index)->toBe(14)
        ->and($withinHours->text)->toBe('на протязі 2 годин')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 02:00:00')
        ->and($withinHours->start->isCertain('hour'))->toBeTrue()
        ->and(Chrono::uk()->parse('Температура 101,194 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('Температура 101 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parse('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 101 стіл!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('Це в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parse('7-730', '2012-08-10'))->toBe([]);
});

it('matches upstream ukrainian positive casual relative time units', function (string $text, string $expected) {
    $result = Chrono::uk()->parse($text, '2016-10-01 12:00')[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expected);
})->with([
    ['наступні 2 тижні', '2016-10-15 12:00:00'],
    ['наступні 2 дні', '2016-10-03 12:00:00'],
    ['наступні два роки', '2018-10-01 12:00:00'],
    ['наступні 2 тижні 3 дні', '2016-10-18 12:00:00'],
    ['через декілька хвилин', '2016-10-01 12:02:00'],
    ['через півгодини', '2016-10-01 12:30:00'],
    ['через 2 години', '2016-10-01 14:00:00'],
    ['через три місяці', '2017-01-01 12:00:00'],
    ['через тиждень', '2016-10-08 12:00:00'],
    ['через місяць', '2016-11-01 12:00:00'],
    ['через рік', '2017-10-01 12:00:00'],
]);

it('matches upstream ukrainian negative casual relative time units', function (string $text, string $expected) {
    $result = Chrono::uk()->parse($text, '2016-10-01 12:00')[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expected);
})->with([
    ['минулі 2 тижні', '2016-09-17 12:00:00'],
    ['минулі два дні', '2016-09-29 12:00:00'],
]);

it('matches upstream ukrainian signed casual relative time units', function (string $text, string $reference, string $expected) {
    $result = Chrono::uk()->parse($text, $reference)[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expected);
})->with([
    ['+15 хвилин', '2012-07-10 12:14', '2012-07-10 12:29:00'],
    ['+15хв', '2012-07-10 12:14', '2012-07-10 12:29:00'],
    ['+1 день 2 години', '2012-07-10 12:14', '2012-07-11 14:14:00'],
    ['-3 роки', '2015-07-10 12:14', '2012-07-10 12:14:00'],
]);
