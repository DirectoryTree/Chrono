<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses spanish casual dates and times', function () {
    $spanish = Chrono::spanish();
    $now = $spanish->parseText('La fecha límite es ahora', '2012-08-10 08:09:10.011')[0];
    $today = $spanish->parseText('La fecha límite es hoy', '2012-08-10 12:00')[0];
    $tomorrow = $spanish->parseText('La fecha límite es Mañana', '2012-08-10 12:00')[0];
    $yesterday = $spanish->parseText('La fecha límite fue ayer', '2012-08-10 12:00')[0];
    $lastNight = $spanish->parseText('La fecha límite fue ayer de noche ', '2012-08-10 12:00')[0];
    $morning = $spanish->parseText('La fecha límite fue esta mañana ', '2012-08-10 12:00')[0];
    $afternoon = $spanish->parseText('La fecha límite fue esta tarde ', '2012-08-10 12:00')[0];
    $todayAtFive = $spanish->parseText('La fecha límite es hoy a las 5PM', '2012-08-10 12:00')[0];
    $tonight = $spanish->parseText('esta noche', '2012-01-01 12:00')[0];
    $tonightEight = $spanish->parseText('esta noche 8pm', '2012-01-01 12:00')[0];
    $tonightAtEight = $spanish->parseText('esta noche a las 8', '2012-01-01 12:00')[0];
    $noon = $spanish->parseText('el mediodía', '2020-09-01 11:00')[0];
    $midnight = $spanish->parseText('la medianoche', '2020-09-01 11:00')[0];

    expect($now->index)->toBe(20)
        ->and($now->text)->toBe('ahora')
        ->and($now->start->get('year'))->toBe(2012)
        ->and($now->start->get('month'))->toBe(8)
        ->and($now->start->get('day'))->toBe(10)
        ->and($now->start->get('hour'))->toBe(8)
        ->and($now->start->get('minute'))->toBe(9)
        ->and($now->start->get('second'))->toBe(10)
        ->and($now->start->get('millisecond'))->toBe(11)
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/ESCasualDateParser')
        ->and($today->index)->toBe(20)
        ->and($today->text)->toBe('hoy')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($spanish->parseDateText('La fecha limite es hoy', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($tomorrow->index)->toBe(20)
        ->and($tomorrow->text)->toBe('Mañana')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($spanish->parseDateText('La fecha limite es Mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00')
        ->and($yesterday->index)->toBe(21)
        ->and($yesterday->text)->toBe('ayer')
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($spanish->parseDateText('La fecha limite fue ayer', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($lastNight->index)->toBe(21)
        ->and($lastNight->text)->toBe('ayer de noche')
        ->and($lastNight->start->get('year'))->toBe(2012)
        ->and($lastNight->start->get('month'))->toBe(8)
        ->and($lastNight->start->get('day'))->toBe(9)
        ->and($lastNight->start->get('hour'))->toBe(22)
        ->and($spanish->parseDateText('ayer de noche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 22:00:00')
        ->and($morning->index)->toBe(21)
        ->and($morning->text)->toBe('esta mañana')
        ->and($morning->start->get('year'))->toBe(2012)
        ->and($morning->start->get('month'))->toBe(8)
        ->and($morning->start->get('day'))->toBe(10)
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($spanish->parseDateText('esta mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($afternoon->index)->toBe(21)
        ->and($afternoon->text)->toBe('esta tarde')
        ->and($afternoon->start->get('year'))->toBe(2012)
        ->and($afternoon->start->get('month'))->toBe(8)
        ->and($afternoon->start->get('day'))->toBe(10)
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($spanish->parseDateText('esta tarde', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($tonight->text)->toBe('esta noche')
        ->and($tonight->start->get('year'))->toBe(2012)
        ->and($tonight->start->get('month'))->toBe(1)
        ->and($tonight->start->get('day'))->toBe(1)
        ->and($tonight->start->get('hour'))->toBe(22)
        ->and($tonight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($spanish->parseDateText('esta noche', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 22:00:00')
        ->and($tonightEight->text)->toBe('esta noche 8pm')
        ->and($tonightEight->start->get('hour'))->toBe(20)
        ->and($tonightEight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($spanish->parseDateText('esta noche 8pm', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($tonightAtEight->text)->toBe('esta noche a las 8')
        ->and($tonightAtEight->start->get('hour'))->toBe(20)
        ->and($tonightAtEight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($spanish->parseDateText('esta noche a las 8', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($todayAtFive->index)->toBe(20)
        ->and($todayAtFive->text)->toBe('hoy a las 5PM')
        ->and($todayAtFive->start->get('year'))->toBe(2012)
        ->and($todayAtFive->start->get('month'))->toBe(8)
        ->and($todayAtFive->start->get('day'))->toBe(10)
        ->and($todayAtFive->start->get('hour'))->toBe(17)
        ->and($spanish->parseDateText('La fecha límite es hoy a las 5PM', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($spanish->parseDateText('el mediodía', '2020-09-01 11:00')?->toDateTimeString())
        ->toBe('2020-09-01 12:00:00')
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($spanish->parseDateText('la medianoche', '2020-09-01 11:00')?->toDateTimeString())
        ->toBe('2020-09-02 00:00:00')
        ->and($spanish->parseText('nohoy', '2012-08-10'))->toBe([])
        ->and($spanish->parseText('hymañana', '2012-08-10'))->toBe([])
        ->and($spanish->parseText('xayer', '2012-08-10'))->toBe([])
        ->and($spanish->parseText('porhora', '2012-08-10'))->toBe([])
        ->and($spanish->parseText('ahoraxsd', '2012-08-10'))->toBe([]);
});

it('parses spanish casual time references', function () {
    $spanish = Chrono::spanish();

    expect($spanish->parseText('Nos vemos esta mañana', '2012-08-10 12:00')[0]->text)
        ->toBe('esta mañana')
        ->and($spanish->parseText('Nos vemos tarde', '2012-08-10 12:00')[0]->start->tags())->toContain('parser/ESCasualTimeParser')
        ->and($spanish->parseDateText('Nos vemos esta mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($spanish->parseDateText('Nos vemos tarde', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($spanish->parseDateText('Nos vemos noche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 22:00:00')
        ->and($spanish->parseDateText('Nos vemos mediodía', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($spanish->parseDateText('Nos vemos medianoche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($spanish->parseDateText('Nos vemos mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00');
});
