<?php

use Chrono\Chrono;

it('parses spanish schedule-style slash date times', function () {
    $spanish = Chrono::es();
    $range = $spanish->parseText('lunes 4/29/2013 630-930am', '2012-08-10')[0];
    $single = $spanish->parseText('martes 5/1/2013 1115am', '2012-08-10')[0];
    $pm = $spanish->parseText('miércoles 5/3/2013 1230pm', '2012-08-10')[0];
    $sunday = $spanish->parseText('domingo 5/6/2013  750am-910am', '2012-08-10')[0];
    $laterMonday = $spanish->parseText('lunes 5/13/2013 630-930am', '2012-08-10')[0];
    $laterWednesday = $spanish->parseText('miércoles 5/15/2013 1030am', '2012-08-10')[0];
    $colon = $spanish->parseText('jueves 6/21/2013 2:30', '2012-08-10')[0];
    $spaced = $spanish->parseText('martes 7/2/2013 1-230 pm', '2012-08-10')[0];
    $commaRange = $spanish->parseText('Lunes, 6/24/2013, 7:00pm - 8:30pm', '2012-08-10')[0];
    $monthName = $spanish->parseText('Miércoles, 3 Julio de 2013 a las 2pm', '2012-08-10')[0];

    expect($range->text)->toBe('lunes 4/29/2013 630-930am')
        ->and($range->start->date()->toDateTimeString())->toBe('2013-04-29 06:30:00')
        ->and($range->start->tags())->toContain('parser/ESScheduleDateTimeParser')
        ->and($range->end?->date()->toDateTimeString())->toBe('2013-04-29 09:30:00')
        ->and($range->end?->tags())->toContain('parser/ESScheduleDateTimeParser')
        ->and($range->start->isCertain('weekday'))->toBeTrue()
        ->and($single->text)->toBe('martes 5/1/2013 1115am')
        ->and($single->start->date()->toDateTimeString())->toBe('2013-05-01 11:15:00')
        ->and($pm->text)->toBe('miércoles 5/3/2013 1230pm')
        ->and($pm->start->date()->toDateTimeString())->toBe('2013-05-03 12:30:00')
        ->and($sunday->text)->toBe('domingo 5/6/2013  750am-910am')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2013-05-06 07:50:00')
        ->and($sunday->end?->date()->toDateTimeString())->toBe('2013-05-06 09:10:00')
        ->and($laterMonday->text)->toBe('lunes 5/13/2013 630-930am')
        ->and($laterMonday->start->date()->toDateTimeString())->toBe('2013-05-13 06:30:00')
        ->and($laterMonday->end?->date()->toDateTimeString())->toBe('2013-05-13 09:30:00')
        ->and($laterWednesday->text)->toBe('miércoles 5/15/2013 1030am')
        ->and($laterWednesday->start->date()->toDateTimeString())->toBe('2013-05-15 10:30:00')
        ->and($colon->text)->toBe('jueves 6/21/2013 2:30')
        ->and($colon->start->date()->toDateTimeString())->toBe('2013-06-21 02:30:00')
        ->and($spaced->text)->toBe('martes 7/2/2013 1-230 pm')
        ->and($spaced->start->date()->toDateTimeString())->toBe('2013-07-02 13:00:00')
        ->and($spaced->end?->date()->toDateTimeString())->toBe('2013-07-02 14:30:00')
        ->and($commaRange->text)->toBe('Lunes, 6/24/2013, 7:00pm - 8:30pm')
        ->and($commaRange->start->date()->toDateTimeString())->toBe('2013-06-24 19:00:00')
        ->and($commaRange->end?->date()->toDateTimeString())->toBe('2013-06-24 20:30:00')
        ->and($monthName->text)->toBe('Miércoles, 3 Julio de 2013 a las 2pm')
        ->and($monthName->start->date()->toDateTimeString())->toBe('2013-07-03 14:00:00');
});

it('parses spanish slash dates', function () {
    $spanish = Chrono::es();
    $monday = $spanish->parseText('lunes 8/2/2016', '2012-08-10')[0];
    $tuesday = $spanish->parseText('Martes 9/2/2016', '2012-08-10')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('lunes 8/2/2016')
        ->and($monday->start->get('year'))->toBe(2016)
        ->and($monday->start->get('month'))->toBe(2)
        ->and($monday->start->get('day'))->toBe(8)
        ->and($monday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->tags())->toContain('parser/ESSlashDateParser')
        ->and($tuesday->index)->toBe(0)
        ->and($tuesday->text)->toBe('Martes 9/2/2016')
        ->and($tuesday->start->get('year'))->toBe(2016)
        ->and($tuesday->start->get('month'))->toBe(2)
        ->and($tuesday->start->get('day'))->toBe(9)
        ->and($tuesday->start->date()->toDateTimeString())->toBe('2016-02-09 12:00:00')
        ->and($spanish->parseDateText('8/2', '2012-08-10', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2013-02-08 12:00:00');
});
