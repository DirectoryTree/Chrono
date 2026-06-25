<?php

use Chrono\Chrono;

it('extracts french timezones from weekday time expressions', function () {
    $french = Chrono::fr();
    $plain = $french->parseText('Vendredi à 2 pm', '2016-04-28')[0];
    $est = $french->parseText('vendredi 2 pm EST', '2016-04-28')[0];
    $cet = $french->parseText('vendredi 15h CET', '2016-02-28')[0];
    $cest = $french->parseText('vendredi 15h cest', '2016-02-28')[0];
    $lowerEst = $french->parseText('Vendredi à 2 pm est', '2016-04-28')[0];
    $sentence = $french->parseText("Vendredi à 2 pm j'ai rdv...", '2016-04-28')[0];
    $sentenceWords = $french->parseText('Vendredi à 2 pm je vais faire quelque chose', '2016-04-28')[0];

    expect($plain->text)->toBe('Vendredi à 2 pm')
        ->and($plain->start->timezoneOffset())->toBeNull()
        ->and($plain->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($est->text)->toBe('vendredi 2 pm EST')
        ->and($est->start->date()->toDateTimeString())->toBe('2016-04-29 14:00:00')
        ->and($est->start->timezoneOffset())->toBe(-300)
        ->and($est->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cet->text)->toBe('vendredi 15h CET')
        ->and($cet->start->timezoneOffset())->toBe(60)
        ->and($cet->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cest->text)->toBe('vendredi 15h cest')
        ->and($cest->start->timezoneOffset())->toBe(120)
        ->and($lowerEst->text)->toBe('Vendredi à 2 pm est')
        ->and($lowerEst->start->timezoneOffset())->toBe(-300)
        ->and($sentence->text)->toBe('Vendredi à 2 pm')
        ->and($sentence->start->timezoneOffset())->toBeNull()
        ->and($sentence->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($sentenceWords->text)->toBe('Vendredi à 2 pm')
        ->and($sentenceWords->start->timezoneOffset())->toBeNull()
        ->and($sentenceWords->start->isCertain('timezoneOffset'))->toBeFalse();
});

it('parses french weekdays', function () {
    $french = Chrono::fr();
    $monday = $french->parseText('Lundi', '2012-08-09')[0];
    $forwardMonday = $french->parseText('Lundi', '2012-08-09', ['forwardDate' => true])[0];
    $thursday = $french->parseText('Jeudi', '2012-08-09')[0];
    $sunday = $french->parseText('Dimanche', '2012-08-09')[0];
    $lastFriday = $french->parseText('la deadline était vendredi dernier...', '2012-08-09')[0];
    $nextFriday = $french->parseText('Planifions une réuinion vendredi prochain', '2015-04-18')[0];
    $monthOverlap = $french->parseText('Dimanche 7 décembre 2014', '2012-08-09')[0];
    $slashOverlap = $french->parseText('Dimanche 7/12/2014', '2012-08-09')[0];

    expect($monday->text)->toBe('Lundi')
        ->and($monday->index)->toBe(0)
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->tags())->toContain('parser/FRWeekdayParser')
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($forwardMonday->index)->toBe(0)
        ->and($forwardMonday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($thursday->text)->toBe('Jeudi')
        ->and($thursday->index)->toBe(0)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($sunday->text)->toBe('Dimanche')
        ->and($sunday->index)->toBe(0)
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($lastFriday->text)->toBe('vendredi dernier')
        ->and($lastFriday->index)->toBe(19)
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($nextFriday->text)->toBe('vendredi prochain')
        ->and($nextFriday->index)->toBe(25)
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextFriday->start->get('weekday'))->toBe(5)
        ->and($monthOverlap->text)->toBe('Dimanche 7 décembre 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($slashOverlap->text)->toBe('Dimanche 7/12/2014')
        ->and($slashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($slashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('weekday'))->toBeTrue();
});
