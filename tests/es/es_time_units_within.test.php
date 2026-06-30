<?php

use DirectoryTree\Chrono\Chrono;

it('parses spanish relative durations', function () {
    $spanish = Chrono::es();
    $fiveDays = $spanish->parseText('Tenemos que hacer algo en 5 días.', '2012-08-10 00:00')[0];
    $fiveMinutes = $spanish->parseText('en 5 minutos', '2012-08-10 12:14')[0];
    $timer = $spanish->parseText('establecer un temporizador de 5 minutos', '2012-08-10 12:14')[0];
    $movingCar = $spanish->parseText('En 5 segundos un auto se moverá', '2012-08-10 12:14')[0];
    $twoWeeks = $spanish->parseText('en dos semanas', '2012-08-10 12:14')[0];
    $oneMonth = $spanish->parseText('dentro de un mes', '2012-08-10 07:14')[0];
    $oneYear = $spanish->parseText('en un año', '2012-08-10 12:14')[0];
    $uppercaseMinutes = $spanish->parseText('En 5 Minutos hay que mover un coche', '2012-08-10 12:14')[0];

    expect($fiveDays->text)->toBe('en 5 días')
        ->and($fiveDays->index)->toBe(23)
        ->and($fiveDays->start->get('year'))->toBe(2012)
        ->and($fiveDays->start->get('month'))->toBe(8)
        ->and($fiveDays->start->get('day'))->toBe(15)
        ->and($fiveDays->start->isCertain('year'))->toBeTrue()
        ->and($fiveDays->start->isCertain('month'))->toBeTrue()
        ->and($fiveDays->start->isCertain('day'))->toBeTrue()
        ->and($fiveDays->start->isCertain('hour'))->toBeFalse()
        ->and($fiveDays->start->tags())->toContain('parser/ESTimeUnitWithinFormatParser')
        ->and($spanish->parseDateText('Tenemos que hacer algo en cinco días.', '2012-08-10 11:12')?->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($fiveMinutes->text)->toBe('en 5 minutos')
        ->and($fiveMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($fiveMinutes->start->isCertain('year'))->toBeTrue()
        ->and($fiveMinutes->start->isCertain('month'))->toBeTrue()
        ->and($fiveMinutes->start->isCertain('day'))->toBeTrue()
        ->and($fiveMinutes->start->isCertain('hour'))->toBeTrue()
        ->and($fiveMinutes->start->isCertain('minute'))->toBeTrue()
        ->and($fiveMinutes->start->tags())->toContain('result/relativeDateAndTime')
        ->and($spanish->parseDateText('por 5 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseDateText('en 1 hora', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($spanish->parseDateText('durante dos horas y tres minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 14:17:00')
        ->and($spanish->parseDateText('de 3 días', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-13 12:14:00')
        ->and($timer->index)
        ->toBe(27)
        ->and($timer->text)
        ->toBe('de 5 minutos')
        ->and($spanish->parseDateText('establecer un temporizador de 5 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseText('En 5 minutos me voy a casa', '2012-08-10 12:14')[0]->text)
        ->toBe('En 5 minutos')
        ->and($movingCar->text)
        ->toBe('En 5 segundos')
        ->and($movingCar->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
        ->and($movingCar->start->isCertain('second'))->toBeTrue()
        ->and($twoWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($twoWeeks->start->isCertain('day'))->toBeTrue()
        ->and($twoWeeks->start->isCertain('weekday'))->toBeFalse()
        ->and($oneMonth->text)->toBe('dentro de un mes')
        ->and($oneMonth->start->date()->toDateTimeString())->toBe('2012-09-10 07:14:00')
        ->and($oneMonth->start->isCertain('month'))->toBeTrue()
        ->and($oneMonth->start->isCertain('day'))->toBeFalse()
        ->and($spanish->parseDateText('en algunos meses', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($oneYear->text)->toBe('en un año')
        ->and($oneYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($oneYear->start->isCertain('year'))->toBeTrue()
        ->and($oneYear->start->isCertain('month'))->toBeFalse()
        ->and($spanish->parseDateText('dentro de un año', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($uppercaseMinutes->text)
        ->toBe('En 5 Minutos')
        ->and($uppercaseMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseText('En 5 minutos hay que mover un coche.', '2012-08-10 12:14')[0]->text)
        ->toBe('En 5 minutos')
        ->and($spanish->parseDateText('En 5 minutos hay que mover un coche.', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseText('durante dos horas', '2012-08-10 12:14')[0]->tags())
        ->toContain('result/relativeDate');
});
