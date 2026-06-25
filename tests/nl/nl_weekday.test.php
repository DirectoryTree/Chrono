<?php

use Chrono\Chrono;

it('parses dutch weekday references', function () {
    $dutch = Chrono::nl();

    $weekday = $dutch->parseText('Afspraak op woensdag', '2012-08-10')[0];
    $monday = $dutch->parseText('maandag', '2012-08-09')[0];
    $forwardMonday = $dutch->parseText('maandag', '2012-08-09', ['forwardDate' => true])[0];
    $thursday = $dutch->parseText('donderdag', '2012-08-09')[0];
    $sunday = $dutch->parseText('zondag', '2012-08-09')[0];
    $lastFriday = $dutch->parseText('De deadline is vorige vrijdag...', '2012-08-09')[0];
    $lastFridayFromSunday = $dutch->parseText('De deadline is vorige vrijdag...', '2012-08-12')[0];
    $nextFriday = $dutch->parseText('Laten we een meeting hebben op volgende week vrijdag', '2015-04-16')[0];
    $nextTuesday = $dutch->parseText('Ik plan een vrije dag op volgende week dinsdag', '2015-04-18')[0];
    $weekdayTime = $dutch->parseText('Laten we op dinsdag ochtend afspreken', '2015-04-18')[0];
    $monthOverlap = $dutch->parseText('zondag, 7 december 2014', '2012-08-09')[0];
    $slashOverlap = $dutch->parseText('zondag 7/12/2014', '2012-08-09')[0];
    $forwardRange = $dutch->parseText('deze vrijdag tot deze maandag', '2016-08-04', ['forwardDate' => true])[0];

    expect($weekday->text)
        ->toBe('op woensdag')
        ->and($weekday->start->tags())->toContain('parser/NLWeekdayParser')
        ->and($dutch->parseDateText('Afspraak op woensdag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-08 12:00:00')
        ->and($dutch->parseDateText('Afspraak volgende maandag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($dutch->parseDateText('Afspraak vorige maandag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($dutch->parseDateText('Afspraak deze vrijdag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($dutch->parseDateText('Afspraak op zo.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and($monday->index)->toBe(0)
        ->and($monday->text)->toBe('maandag')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($forwardMonday->start->get('day'))->toBe(13)
        ->and($forwardMonday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($thursday->start->get('day'))->toBe(9)
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($sunday->start->get('day'))->toBe(12)
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($lastFriday->index)->toBe(15)
        ->and($lastFriday->text)->toBe('vorige vrijdag')
        ->and($lastFriday->start->get('day'))->toBe(3)
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($lastFridayFromSunday->start->get('day'))->toBe(10)
        ->and($lastFridayFromSunday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($nextFriday->index)->toBe(28)
        ->and($nextFriday->text)->toBe('op volgende week vrijdag')
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextTuesday->index)->toBe(22)
        ->and($nextTuesday->text)->toBe('op volgende week dinsdag')
        ->and($nextTuesday->start->date()->toDateTimeString())->toBe('2015-04-21 12:00:00')
        ->and($weekdayTime->index)->toBe(9)
        ->and($weekdayTime->text)->toBe('op dinsdag ochtend')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2015-04-21 06:00:00')
        ->and($weekdayTime->start->get('weekday'))->toBe(2)
        ->and($monthOverlap->text)->toBe('zondag, 7 december 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($slashOverlap->text)->toBe('zondag 7/12/2014')
        ->and($slashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($slashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($forwardRange->text)->toBe('deze vrijdag tot deze maandag')
        ->and($forwardRange->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($forwardRange->start->get('weekday'))->toBe(5)
        ->and($forwardRange->start->isCertain('day'))->toBeFalse()
        ->and($forwardRange->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($forwardRange->end?->get('weekday'))->toBe(1)
        ->and($forwardRange->end?->isCertain('day'))->toBeFalse();
});
