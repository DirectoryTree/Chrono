<?php

use Chrono\Chrono;

it('parses russian weekdays times and relative durations', function () {
    $weekday = Chrono::ru()->parseText('среда', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::ru()->parseText('следующий понедельник', '2012-08-10 09:30')[0];
    $timeWithSeconds = Chrono::ru()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $time = Chrono::ru()->parseText('в 6:30 вечера', '2012-08-10 09:30')[0];
    $timeRange = Chrono::ru()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morningTime = Chrono::ru()->parseText('в 11 утра', '2016-10-01 08:00')[0];
    $eveningTime = Chrono::ru()->parseText('в 11 вечера', '2016-10-01 08:00')[0];
    $morningRange = Chrono::ru()->parseText('с 10 до 11 утра', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::ru()->parseText('с 10 до 11 вечера', '2016-10-01 08:00')[0];
    $casualHour = Chrono::russian()->parseText('в 1', '2016-10-01 08:00')[0];
    $casualNoon = Chrono::russian()->parseText('в 12', '2016-10-01 08:00')[0];
    $casualDotted = Chrono::russian()->parseText('в 12.30', '2016-10-01 08:00')[0];
    $ago = Chrono::ru()->parseText('2 дня назад', '2012-08-10 09:30')[0];
    $halfHourAgo = Chrono::ru()->parseText('полчаса назад что-то было', '2012-07-10 00:00')[0];
    $pairMinutes = Chrono::ru()->parseText('через пару минут', '2016-10-01 12:00')[0];
    $later = Chrono::ru()->parseText('через 3 недели', '2012-08-10 09:30')[0];
    $within = Chrono::ru()->parseText('в течение 1 месяца', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::ru()->parseText('будет сделано в течение минуты', '2012-08-10 00:00')[0];
    $withinHours = Chrono::ru()->parseText('будет сделано в течение 2 часов.', '2012-08-10 00:00')[0];
    $thisWeek = Chrono::ru()->parseText('на этой неделе', '2017-11-19 12:00')[0];
    $thisMonth = Chrono::ru()->parseText('в этом месяце', '2017-11-19 12:00')[0];
    $firstOfThisMonth = Chrono::ru()->parseText('в этом месяце', '2017-11-01 12:00')[0];
    $thisYear = Chrono::ru()->parseText('в этом году', '2017-11-19 12:00')[0];
    $lastWeek = Chrono::ru()->parseText('на прошлой неделе', '2016-10-01 12:00')[0];
    $lastMonth = Chrono::ru()->parseText('в прошлом месяце', '2016-10-01 12:00')[0];
    $nextWeek = Chrono::ru()->parseText('на следующей неделе', '2016-10-01 12:00')[0];
    $nextMonth = Chrono::ru()->parseText('в следующем месяце', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::ru()->parseText('в следующем квартале', '2016-10-01 12:00')[0];
    $lastYear = Chrono::ru()->parseText('в прошлом году', '2016-10-01 12:00')[0];
    $nextYear = Chrono::ru()->parseText('в следующем году', '2016-10-01 12:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($timeWithSeconds->index)->toBe(0)
        ->and($timeWithSeconds->text)->toBe('20:32:13')
        ->and($timeWithSeconds->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/RUTimeExpressionParser')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morningTime->index)->toBe(0)
        ->and($morningTime->text)->toBe('в 11 утра')
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningTime->index)->toBe(0)
        ->and($eveningTime->text)->toBe('в 11 вечера')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($casualHour->index)->toBe(0)
        ->and($casualHour->text)->toBe('в 1')
        ->and($casualHour->start->get('hour'))->toBe(1)
        ->and($casualNoon->index)->toBe(0)
        ->and($casualNoon->text)->toBe('в 12')
        ->and($casualNoon->start->get('hour'))->toBe(12)
        ->and($casualDotted->index)->toBe(0)
        ->and($casualDotted->text)->toBe('в 12.30')
        ->and($casualDotted->start->get('hour'))->toBe(12)
        ->and($casualDotted->start->get('minute'))->toBe(30)
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/RUTimeUnitAgoFormatParser')
        ->and($halfHourAgo->index)->toBe(0)
        ->and($halfHourAgo->text)->toBe('полчаса назад')
        ->and($halfHourAgo->start->date()->toDateTimeString())->toBe('2012-07-09 23:30:00')
        ->and($pairMinutes->text)->toBe('через пару минут')
        ->and($pairMinutes->start->date()->toDateTimeString())->toBe('2016-10-01 12:02:00')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/RUTimeUnitCasualRelativeFormatParser')
        ->and($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/RUTimeUnitWithinFormatParser')
        ->and($within->start->isCertain('month'))->toBeTrue()
        ->and($within->start->isCertain('day'))->toBeFalse()
        ->and($withinMinute->index)->toBe(26)
        ->and($withinMinute->text)->toBe('в течение минуты')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and($withinMinute->start->isCertain('hour'))->toBeTrue()
        ->and($withinMinute->start->isCertain('minute'))->toBeTrue()
        ->and($withinMinute->start->tags())->toContain('result/relativeDateAndTime')
        ->and($withinHours->index)->toBe(26)
        ->and($withinHours->text)->toBe('в течение 2 часов')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 02:00:00')
        ->and($withinHours->start->isCertain('hour'))->toBeTrue()
        ->and($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($thisWeek->start->tags())->toContain('parser/RURelativeDateFormatParser')
        ->and($thisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($firstOfThisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($thisYear->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00')
        ->and($lastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($lastMonth->start->date()->toDateTimeString())->toBe('2016-09-01 12:00:00')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2016-10-08 12:00:00')
        ->and($nextMonth->start->date()->toDateTimeString())->toBe('2016-11-01 12:00:00')
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00')
        ->and($lastYear->start->date()->toDateTimeString())->toBe('2015-10-01 12:00:00')
        ->and($nextYear->start->date()->toDateTimeString())->toBe('2017-10-01 12:00:00')
        ->and(Chrono::ru()->parseText('Температура 101,194 градусов!', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 101 градусов!', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 101 стул!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('7-730', '2012-08-10'))->toBe([]);
});
