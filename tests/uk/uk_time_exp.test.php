<?php

use DirectoryTree\Chrono\Chrono;

it('parses ukrainian time expressions', function () {
    $time = Chrono::uk()->parseText('о 6:30 вечора', '2012-08-10 09:30')[0];
    $fullTime = Chrono::uk()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $timeRange = Chrono::uk()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morning = Chrono::uk()->parseText('об 11 ранку', '2016-10-01 08:00')[0];
    $evening = Chrono::uk()->parseText('в 11 вечора', '2016-10-01 08:00')[0];

    expect($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/UKTimeExpressionParser')
        ->and($fullTime->text)->toBe('20:32:13')
        ->and($fullTime->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morning->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00');
});

it('parses ukrainian time ranges with meridiem handling', function () {
    $morningRange = Chrono::uk()->parseText('з 10 до 11 ранку', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::uk()->parseText('із 10 до 11 вечора', '2016-10-01 08:00')[0];

    expect($morningRange->index)->toBe(0)
        ->and($morningRange->text)->toBe('з 10 до 11 ранку')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->index)->toBe(0)
        ->and($eveningRange->text)->toBe('із 10 до 11 вечора')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00');
});

it('parses ukrainian casual numeric time expressions', function () {
    $hour = Chrono::ukrainian()->parseText('в 1', '2016-10-01 08:00')[0];
    $noon = Chrono::ukrainian()->parseText('о 12', '2016-10-01 08:00')[0];
    $dotted = Chrono::ukrainian()->parseText('в 12.30', '2016-10-01 08:00')[0];

    expect($hour->index)->toBe(0)
        ->and($hour->text)->toBe('в 1')
        ->and($hour->start->get('hour'))->toBe(1)
        ->and($noon->index)->toBe(0)
        ->and($noon->text)->toBe('о 12')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($dotted->index)->toBe(0)
        ->and($dotted->text)->toBe('в 12.30')
        ->and($dotted->start->get('hour'))->toBe(12)
        ->and($dotted->start->get('minute'))->toBe(30);
});

it('does not parse ukrainian year-like and numeric non-time expressions', function () {
    expect(Chrono::uk()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 101,194 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 101 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([]);
});

it('does not parse ukrainian strict numeric non-time expressions', function () {
    expect(Chrono::strictUkrainian()->parseText('Це в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 101 стіл!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('7-730', '2012-08-10'))->toBe([]);
});
