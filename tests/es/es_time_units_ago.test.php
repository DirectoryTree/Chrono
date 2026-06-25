<?php

use Chrono\Chrono;

it('parses spanish past relative durations', function () {
    $spanish = Chrono::es();

    expect($spanish->parseText('hace 5 días, hicimos algo', '2012-08-10')[0]->text)
        ->toBe('hace 5 días')
        ->and($spanish->parseText('hace 5 días, hicimos algo', '2012-08-10')[0]->tags())
        ->toContain('parser/ESTimeUnitAgoFormatParser')
        ->and($spanish->parseDateText('hace 5 días, hicimos algo', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 00:00:00')
        ->and($spanish->parseDateText('hace 15 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and($spanish->parseDateText('hace 12 horas', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($spanish->parseDateText('hace 5 meses, hicimos algo', '2012-10-10')?->toDateTimeString())
        ->toBe('2012-05-10 00:00:00')
        ->and($spanish->parseDateText('hace 5 años, hicimos algo', '2012-08-10 22:22')?->toDateTimeString())
        ->toBe('2007-08-10 22:22:00')
        ->and($spanish->parseDateText('hace una semana, hicimos algo', '2012-08-03 08:34')?->toDateTimeString())
        ->toBe('2012-07-27 08:34:00');
});
