<?php

use Chrono\Chrono;

it('parses spanish month name dates', function () {
    $spanish = Chrono::es();
    $explicit = $spanish->parseText('10 Agosto 2012', '2012-08-10')[0];
    $bc = $spanish->parseText('10 Agosto 234 AC', '2012-08-10')[0];
    $ad = $spanish->parseText('10 Agosto 88 d. C.', '2012-08-10')[0];
    $compact = $spanish->parseText('Dom 15Sep', '2013-08-10')[0];
    $uppercaseCompact = $spanish->parseText('DOM 15SEP', '2013-08-10')[0];
    $prefixed = $spanish->parseText('La fecha límite es 10 Agosto', '2012-08-10')[0];
    $inferred = $spanish->parseText('La fecha limite es el martes, 10 de enero', '2012-08-10')[0];
    $accentedInferred = $spanish->parseText('La fecha límite es el miércoles, 10 de enero ', '2012-08-10')[0];
    $deDate = $spanish->parseText('10 de Agosto de 2012', '2010-02-01')[0];
    $withTime = $spanish->parseText('12 de julio a las 19:00', '2012-08-10')[0];

    expect($explicit->index)->toBe(0)
        ->and($explicit->text)->toBe('10 Agosto 2012')
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($bc->start->get('year'))->toBe(-234)
        ->and($bc->start->date()->month)->toBe(8)
        ->and($bc->start->date()->day)->toBe(10)
        ->and($ad->text)->toBe('10 Agosto 88 d. C.')
        ->and($ad->start->get('year'))->toBe(88)
        ->and($compact->index)->toBe(0)
        ->and($compact->text)->toBe('Dom 15Sep')
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($uppercaseCompact->index)->toBe(0)
        ->and($uppercaseCompact->text)->toBe('DOM 15SEP')
        ->and($uppercaseCompact->start->get('year'))->toBe(2013)
        ->and($uppercaseCompact->start->get('month'))->toBe(9)
        ->and($uppercaseCompact->start->get('day'))->toBe(15)
        ->and($prefixed->index)->toBe(20)
        ->and($prefixed->text)->toBe('10 Agosto')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($inferred->text)->toBe('martes, 10 de enero')
        ->and($inferred->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($inferred->start->isCertain('weekday'))->toBeTrue()
        ->and($accentedInferred->index)->toBe(23)
        ->and($accentedInferred->text)->toBe('miércoles, 10 de enero')
        ->and($accentedInferred->start->get('weekday'))->toBe(3)
        ->and($accentedInferred->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($deDate->index)->toBe(0)
        ->and($deDate->text)->toBe('10 de Agosto de 2012')
        ->and($deDate->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($withTime->text)->toBe('12 de julio a las 19:00')
        ->and($withTime->start->date()->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($withTime->start->tags())->toContain('parser/ESMonthNameParser');
});

it('parses spanish month name ranges', function () {
    $spanish = Chrono::es();
    $sameDash = $spanish->parseText('10 - 22 Agosto 2012', '2012-08-10')[0];
    $sameWord = $spanish->parseText('10 a 22 Agosto 2012', '2012-08-10')[0];
    $sameDesde = $spanish->parseText('10º desde 22ª Agosto 2012', '2012-08-10')[0];
    $cross = $spanish->parseText('10 Agosto - 12 Septiembre', '2012-08-10')[0];
    $crossYear = $spanish->parseText('10 Agosto - 12 Septiembre 2013', '2012-08-10')[0];

    expect($sameDash->text)->toBe('10 - 22 Agosto 2012')
        ->and($sameDash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDash->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameWord->text)->toBe('10 a 22 Agosto 2012')
        ->and($sameWord->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameWord->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameDesde->text)->toBe('10º desde 22ª Agosto 2012')
        ->and($sameDesde->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDesde->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($cross->text)->toBe('10 Agosto - 12 Septiembre')
        ->and($cross->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($cross->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossYear->text)->toBe('10 Agosto - 12 Septiembre 2013')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00');
});
