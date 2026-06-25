<?php

use Chrono\Chrono;

it('parses finnish little endian month name dates and ranges', function () {
    $finnish = Chrono::fi();
    $dayMonth = $finnish->parseText('15. elokuuta', '2012-08-10')[0];
    $dayMonthYear = $finnish->parseText('15 elokuuta 2012', '2012-08-10')[0];
    $abbreviated = $finnish->parseText('15. elo 2012', '2012-08-10')[0];
    $closestYear = $finnish->parseText('3 tammikuuta', '2012-08-10')[0];
    $december = $finnish->parseText('1 joulukuuta 2023', '2023-11-01')[0];
    $hyphenRange = $finnish->parseText('15-16 elokuuta', '2012-08-10')[0];
    $range = $finnish->parseText('Tapahtuma 10.-12. elokuuta 2012', '2012-08-10')[0];

    expect($dayMonth->start->get('year'))->toBe(2012)
        ->and($dayMonth->start->get('month'))->toBe(8)
        ->and($dayMonth->start->get('day'))->toBe(15)
        ->and($dayMonthYear->start->get('year'))->toBe(2012)
        ->and($dayMonthYear->start->get('month'))->toBe(8)
        ->and($dayMonthYear->start->get('day'))->toBe(15)
        ->and($abbreviated->start->get('year'))->toBe(2012)
        ->and($abbreviated->start->get('month'))->toBe(8)
        ->and($abbreviated->start->get('day'))->toBe(15)
        ->and($closestYear->start->get('year'))->toBe(2013)
        ->and($closestYear->start->get('month'))->toBe(1)
        ->and($closestYear->start->get('day'))->toBe(3)
        ->and($december->start->get('year'))->toBe(2023)
        ->and($december->start->get('month'))->toBe(12)
        ->and($december->start->get('day'))->toBe(1)
        ->and($hyphenRange->start->get('year'))->toBe(2012)
        ->and($hyphenRange->start->get('month'))->toBe(8)
        ->and($hyphenRange->start->get('day'))->toBe(15)
        ->and($hyphenRange->end?->get('year'))->toBe(2012)
        ->and($hyphenRange->end?->get('month'))->toBe(8)
        ->and($hyphenRange->end?->get('day'))->toBe(16)
        ->and($finnish->parseText('32 elokuuta', '2012-08-10'))->toBe([])
        ->and($finnish->parseText('Tapahtuma 10. elokuuta', '2012-08-10')[0]->text)
        ->toBe('10. elokuuta')
        ->and($finnish->parseText('Tapahtuma 10. elokuuta', '2012-08-10')[0]->start->tags())->toContain('parser/FIMonthNameLittleEndianParser')
        ->and($finnish->parseDateText('Tapahtuma 10. elokuuta', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($finnish->parseDateText('Tapahtuma 10 elokuu 2026', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-08-10 12:00:00')
        ->and($range->text)->toBe('10.-12. elokuuta 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
