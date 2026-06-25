<?php

use Chrono\Chrono;

it('parses german within time units with upstream-shaped components', function () {
    $german = Chrono::de();
    $fiveDays = $german->parseText('Wir müssen etwas in 5 Tagen erledigen.', '2012-08-10')[0];
    $fiveDaysWord = $german->parseText('Wir müssen etwas in fünf Tagen erledigen.', '2012-08-10 11:12')[0];
    $minutes = $german->parseText('in 5 Minuten', '2012-08-10 12:14')[0];
    $forMinutes = $german->parseText('für 5 minuten', '2012-08-10 12:14')[0];
    $hour = $german->parseText('in einer Stunde', '2012-08-10 12:14')[0];
    $timer = $german->parseText('starte einen Timer für 5 Minuten', '2012-08-10 12:14')[0];
    $home = $german->parseText('In 5 Minuten gehe ich nach Hause', '2012-08-10 12:14')[0];
    $seconds = $german->parseText('In 5 Sekunden wird ein Auto fahren', '2012-08-10 12:14')[0];
    $weeks = $german->parseText('in zwei Wochen', '2012-08-10 12:14')[0];
    $month = $german->parseText('in einem Monat', '2012-08-10 07:14')[0];
    $months = $german->parseText('in einigen Monaten', '2012-07-10 22:14')[0];
    $year = $german->parseText('in einem Jahr', '2012-08-10 12:14')[0];
    $years = $german->parseText('in 20 Jahren', '2012-08-10 12:14')[0];
    $abbreviated = $german->parseText('In 5 Min wird ein Auto fahren', '2012-08-10 12:14')[0];

    expect($fiveDays->index)->toBe(18)
        ->and($fiveDays->text)->toBe('in 5 Tagen')
        ->and($fiveDays->start->get('year'))->toBe(2012)
        ->and($fiveDays->start->get('month'))->toBe(8)
        ->and($fiveDays->start->get('day'))->toBe(15)
        ->and($fiveDays->start->isCertain('day'))->toBeTrue()
        ->and($fiveDays->start->isCertain('hour'))->toBeFalse()
        ->and($fiveDays->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($fiveDaysWord->index)->toBe(18)
        ->and($fiveDaysWord->text)->toBe('in fünf Tagen')
        ->and($fiveDaysWord->start->date()->toDateTimeString())->toBe('2012-08-15 11:12:00')
        ->and($minutes->index)->toBe(0)
        ->and($minutes->text)->toBe('in 5 Minuten')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($minutes->start->isCertain('minute'))->toBeTrue()
        ->and($forMinutes->text)->toBe('für 5 minuten')
        ->and($forMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($hour->text)->toBe('in einer Stunde')
        ->and($hour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($timer->index)->toBe(19)
        ->and($timer->text)->toBe('für 5 Minuten')
        ->and($timer->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($home->index)->toBe(0)
        ->and($home->text)->toBe('In 5 Minuten')
        ->and($home->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($seconds->index)->toBe(0)
        ->and($seconds->text)->toBe('In 5 Sekunden')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($seconds->start->isCertain('second'))->toBeTrue()
        ->and($weeks->text)->toBe('in zwei Wochen')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($weeks->start->isCertain('day'))->toBeTrue()
        ->and($month->text)->toBe('in einem Monat')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-09-10 07:14:00')
        ->and($month->start->isCertain('month'))->toBeTrue()
        ->and($month->start->isCertain('day'))->toBeFalse()
        ->and($months->text)->toBe('in einigen Monaten')
        ->and($months->start->date()->toDateTimeString())->toBe('2012-10-10 22:14:00')
        ->and($year->text)->toBe('in einem Jahr')
        ->and($year->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($year->start->isCertain('year'))->toBeTrue()
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($years->text)->toBe('in 20 Jahren')
        ->and($years->start->date()->toDateTimeString())->toBe('2032-08-10 12:14:00')
        ->and($abbreviated->index)->toBe(0)
        ->and($abbreviated->text)->toBe('In 5 Min')
        ->and($abbreviated->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00');
});
