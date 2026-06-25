<?php

use Chrono\Chrono;

it('parses french month name dates', function () {
    $french = Chrono::fr();
    $explicit = $french->parseText('10 Août 2012', '2012-08-10')[0];
    $inferred = $french->parseText('8 Février', '2012-08-10')[0];
    $ordinal = $french->parseText('1er Août 2012', '2012-08-01')[0];
    $bc = $french->parseText('10 Août 234 AC', '2012-08-10')[0];
    $ad = $french->parseText('10 Août 88 p. Chr. n.', '2012-08-10')[0];
    $compact = $french->parseText('Dim 15 Sept', '2013-08-10')[0];
    $attached = $french->parseText('DIM 15SEPT', '2013-08-10')[0];
    $prefixed = $french->parseText('La date limite est le Mardi 10 janvier', '2012-08-10')[0];
    $abbreviatedWeekday = $french->parseText('La date limite est Mar 10 Jan', '2012-08-10')[0];

    expect($explicit->text)->toBe('10 Août 2012')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($inferred->text)->toBe('8 Février')
        ->and($inferred->index)->toBe(0)
        ->and($inferred->start->date()->toDateTimeString())->toBe('2013-02-08 12:00:00')
        ->and($ordinal->text)->toBe('1er Août 2012')
        ->and($ordinal->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($bc->start->get('year'))->toBe(-234)
        ->and($bc->start->get('month'))->toBe(8)
        ->and($bc->start->get('day'))->toBe(10)
        ->and($ad->start->get('year'))->toBe(88)
        ->and($compact->text)->toBe('Dim 15 Sept')
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($attached->text)->toBe('DIM 15SEPT')
        ->and($attached->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($prefixed->text)->toBe('Mardi 10 janvier')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($prefixed->start->tags())->toContain('parser/FRMonthNameParser')
        ->and($prefixed->start->get('weekday'))->toBe(2)
        ->and($abbreviatedWeekday->text)->toBe('Mar 10 Jan')
        ->and($abbreviatedWeekday->index)->toBe(19)
        ->and($abbreviatedWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(2)
        ->and($french->parseDateText('31 mars 2016', '2012-08-10')?->toDateTimeString())->toBe('2016-03-31 12:00:00')
        ->and($french->parseDateText('10 Aout 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($french->parseDateText('10 Fevrier 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-02-10 12:00:00')
        ->and($french->parseDateText('10 Decembre 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-12-10 12:00:00')
        ->and($french->parseText('32 Août 2014', '2012-08-10'))->toBe([])
        ->and($french->parseText('29 Février 2014', '2012-08-10'))->toBe([])
        ->and($french->parseText('32 Aout', '2012-08-10'))->toBe([])
        ->and($french->parseText('29 Fevrier', '2013-08-10'))->toBe([]);
});

it('parses french month name ranges and date times', function () {
    $french = Chrono::fr();
    $sameMonth = $french->parseText('10 - 22 août 2012', '2012-08-10')[0];
    $sameMonthAu = $french->parseText('10 au 22 août 2012', '2012-08-10')[0];
    $sameMonthUntil = $french->parseText("10 jusqu'au 22 août 2012", '2012-08-10')[0];
    $crossMonth = $french->parseText('10 août - 12 septembre', '2012-08-10')[0];
    $crossMonthYear = $french->parseText('10 août - 12 septembre 2013', '2012-08-10')[0];
    $repeatedMonth = $french->parseText('Du 24 août 2023 au 26 août 2023', '2012-08-10')[0];
    $crossYear = $french->parseText('24 décembre au 2 janvier', '2023-12-01')[0];

    expect($sameMonth->text)->toBe('10 - 22 août 2012')
        ->and($sameMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonth->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameMonthAu->text)->toBe('10 au 22 août 2012')
        ->and($sameMonthAu->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthAu->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameMonthUntil->text)->toBe("10 jusqu'au 22 août 2012")
        ->and($sameMonthUntil->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthUntil->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($crossMonth->text)->toBe('10 août - 12 septembre')
        ->and($crossMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonth->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthYear->text)->toBe('10 août - 12 septembre 2013')
        ->and($crossMonthYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($repeatedMonth->text)->toBe('24 août 2023 au 26 août 2023')
        ->and($repeatedMonth->start->date()->toDateTimeString())->toBe('2023-08-24 12:00:00')
        ->and($repeatedMonth->end?->date()->toDateTimeString())->toBe('2023-08-26 12:00:00')
        ->and($crossYear->text)->toBe('24 décembre au 2 janvier')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2023-12-24 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2024-01-02 12:00:00')
        ->and($french->parseDateText('12 juillet à 19:00', '2012-08-10')?->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($french->parseDateText('5 mai 12:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($french->parseDateText('7 Mai 11:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-07 11:00:00');
});
