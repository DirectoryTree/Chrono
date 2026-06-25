<?php

use Chrono\Chrono;

it('parses spanish time expressions', function () {
    $spanish = Chrono::es();
    $single = $spanish->parseText('Estaremos a las 6.13 AM', '2012-08-10 00:00')[0];
    $range = $spanish->parseText(' de 6:30pm a 11:00pm ', '2012-08-10 00:00')[0];
    $alRange = $spanish->parseText('del 6:30pm al 11:00pm', '2012-08-10 00:00')[0];
    $implied = $spanish->parseText('de 1pm a 3', '2012-08-10 00:00')[0];
    $dotRange = $spanish->parseText('8:10 - 12.32', '2012-08-10 00:00')[0];
    $milliseconds = $spanish->parseText('8:10:30.123', '2012-08-10 00:00')[0];
    $dateTime = $spanish->parseText('Algo pasó el 10 de Agosto de 2012 10:12:59 pm', '2012-08-10')[0];
    $lasTwelve = $spanish->parseText('las 12', '2012-08-10 00:00')[0];

    expect($single->index)->toBe(12)
        ->and($single->text)->toBe('las 6.13 AM')
        ->and($single->start->get('hour'))->toBe(6)
        ->and($single->start->get('minute'))->toBe(13)
        ->and($single->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($single->start->tags())->toContain('parser/ESTimeExpressionParser')
        ->and($range->text)->toBe('de 6:30pm a 11:00pm')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($alRange->text)->toBe('del 6:30pm al 11:00pm')
        ->and($alRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($alRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($implied->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($implied->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($dotRange->index)->toBe(0)
        ->and($dotRange->text)->toBe('8:10 - 12.32')
        ->and($dotRange->start->get('hour'))->toBe(8)
        ->and($dotRange->start->get('minute'))->toBe(10)
        ->and($dotRange->start->isCertain('day'))->toBeFalse()
        ->and($dotRange->start->isCertain('month'))->toBeFalse()
        ->and($dotRange->start->isCertain('year'))->toBeFalse()
        ->and($dotRange->start->isCertain('hour'))->toBeTrue()
        ->and($dotRange->start->isCertain('minute'))->toBeTrue()
        ->and($dotRange->start->isCertain('second'))->toBeFalse()
        ->and($dotRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($dotRange->end?->get('hour'))->toBe(12)
        ->and($dotRange->end?->get('minute'))->toBe(32)
        ->and($dotRange->end?->isCertain('day'))->toBeFalse()
        ->and($dotRange->end?->isCertain('month'))->toBeFalse()
        ->and($dotRange->end?->isCertain('year'))->toBeFalse()
        ->and($dotRange->end?->isCertain('hour'))->toBeTrue()
        ->and($dotRange->end?->isCertain('minute'))->toBeTrue()
        ->and($dotRange->end?->isCertain('second'))->toBeFalse()
        ->and($dotRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($dotRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($dateTime->index)->toBe(13)
        ->and($dateTime->text)->toBe('10 de Agosto de 2012 10:12:59 pm')
        ->and($dateTime->start->get('year'))->toBe(2012)
        ->and($dateTime->start->get('month'))->toBe(8)
        ->and($dateTime->start->get('day'))->toBe(10)
        ->and($dateTime->start->get('hour'))->toBe(22)
        ->and($dateTime->start->get('minute'))->toBe(12)
        ->and($dateTime->start->get('second'))->toBe(59)
        ->and($dateTime->start->get('millisecond'))->toBe(0)
        ->and($dateTime->start->isCertain('millisecond'))->toBeFalse()
        ->and($lasTwelve->text)->toBe('las 12')
        ->and($lasTwelve->start->get('hour'))->toBe(12)
        ->and($spanish->parseText('6pm', '2012-08-10 00:00')[0]->text)->toBe('6pm')
        ->and($spanish->parseDateText('6pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($spanish->parseText('6 pm', '2012-08-10 00:00')[0]->text)->toBe('6 pm')
        ->and($spanish->parseDateText('7-10pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 19:00:00')
        ->and($spanish->parseText('7-10pm', '2012-08-10 00:00')[0]->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($spanish->parseText('11.1pm', '2012-08-10 00:00')[0]->text)->toBe('11.1pm')
        ->and($spanish->parseDateText('11.1pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 23:01:00')
        ->and($spanish->parseDateText('Algo pasó el 10 de Agosto de 2012 10:12:59 pm', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 22:12:59');
});
