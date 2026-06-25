<?php

use Chrono\Chrono;

it('parses russian time expressions', function () {
    $timeWithSeconds = Chrono::ru()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $time = Chrono::ru()->parseText('в 6:30 вечера', '2012-08-10 09:30')[0];
    $timeRange = Chrono::ru()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morningTime = Chrono::ru()->parseText('в 11 утра', '2016-10-01 08:00')[0];
    $eveningTime = Chrono::ru()->parseText('в 11 вечера', '2016-10-01 08:00')[0];
    $morningRange = Chrono::ru()->parseText('с 10 до 11 утра', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::ru()->parseText('с 10 до 11 вечера', '2016-10-01 08:00')[0];

    expect($timeWithSeconds->text)->toBe('20:32:13')
        ->and($timeWithSeconds->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/RUTimeExpressionParser')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morningTime->text)->toBe('в 11 утра')
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningTime->text)->toBe('в 11 вечера')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00');
});

it('parses russian casual numeric time expressions', function () {
    $hour = Chrono::russian()->parseText('в 1', '2016-10-01 08:00')[0];
    $noon = Chrono::russian()->parseText('в 12', '2016-10-01 08:00')[0];
    $dotted = Chrono::russian()->parseText('в 12.30', '2016-10-01 08:00')[0];

    expect($hour->index)->toBe(0)
        ->and($hour->text)->toBe('в 1')
        ->and($hour->start->get('hour'))->toBe(1)
        ->and($noon->index)->toBe(0)
        ->and($noon->text)->toBe('в 12')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($dotted->index)->toBe(0)
        ->and($dotted->text)->toBe('в 12.30')
        ->and($dotted->start->get('hour'))->toBe(12)
        ->and($dotted->start->get('minute'))->toBe(30);
});

it('does not parse russian year-like and numeric non-time expressions', function () {
    expect(Chrono::ru()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 101,194 градусов!', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 101 градусов!', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([]);
});

it('does not parse russian strict numeric non-time expressions', function () {
    expect(Chrono::strictRussian()->parseText('Это в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 101 стул!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('7-730', '2012-08-10'))->toBe([]);
});
