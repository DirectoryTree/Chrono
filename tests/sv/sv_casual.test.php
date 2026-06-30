<?php

use DirectoryTree\Chrono\Chrono;

it('parses swedish casual date references', function () {
    $swedish = Chrono::sv();
    $now = $swedish->parseText('nu', '2012-08-10 09:30:45.123')[0];
    $tomorrowMorning = $swedish->parseText('imorgon på morgonen', '2012-08-10 09:30')[0];
    $morning = $swedish->parseText('idag på morgonen', '2012-08-10')[0];
    $forenoon = $swedish->parseText('idag på förmiddagen', '2012-08-10')[0];
    $midday = $swedish->parseText('idag på middagen', '2012-08-10')[0];
    $afternoon = $swedish->parseText('idag på eftermiddagen', '2012-08-10')[0];
    $evening = $swedish->parseText('idag på kvällen', '2012-08-10')[0];
    $night = $swedish->parseText('idag på natten', '2012-08-10')[0];
    $midnight = $swedish->parseText('idag vid midnatt', '2012-08-10 09:30')[0];

    expect($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 09:30:45.123')
        ->and($now->start->tags())->toContain('parser/SVCasualDateParser')
        ->and($swedish->parseDateText('idag', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($swedish->parseDateText('imorgon', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-11 09:30:00')
        ->and($swedish->parseDateText('igår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($swedish->parseDateText('förrgår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($swedish->parseDateText('i förrgår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($tomorrowMorning->text)->toBe('imorgon på morgonen')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($forenoon->start->get('hour'))->toBe(9)
        ->and($midday->start->get('hour'))->toBe(12)
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($evening->start->get('hour'))->toBe(20)
        ->and($night->start->get('hour'))->toBe(2)
        ->and($midnight->text)->toBe('idag vid midnatt')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00');
});

it('parses swedish casual dates with upstream-shaped components', function () {
    $swedish = Chrono::sv();
    $today = $swedish->parseText('idag', '2012-08-10')[0];
    $tomorrow = $swedish->parseText('imorgon', '2012-08-10')[0];
    $yesterday = $swedish->parseText('igår', '2012-08-10')[0];
    $beforeYesterday = $swedish->parseText('förrgår', '2012-08-10')[0];
    $morning = $swedish->parseText('idag på morgonen', '2012-08-10')[0];
    $forenoon = $swedish->parseText('idag på förmiddagen', '2012-08-10')[0];
    $midday = $swedish->parseText('idag på middagen', '2012-08-10')[0];
    $afternoon = $swedish->parseText('idag på eftermiddagen', '2012-08-10')[0];
    $evening = $swedish->parseText('idag på kvällen', '2012-08-10')[0];
    $night = $swedish->parseText('idag på natten', '2012-08-10')[0];
    $midnight = $swedish->parseText('idag vid midnatt', '2012-08-10')[0];

    expect($today->index)->toBe(0)
        ->and($today->text)->toBe('idag')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($today->start->isCertain('year'))->toBeTrue()
        ->and($today->start->isCertain('month'))->toBeTrue()
        ->and($today->start->isCertain('day'))->toBeTrue()
        ->and($today->start->isCertain('hour'))->toBeFalse()
        ->and($tomorrow->text)->toBe('imorgon')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($yesterday->text)->toBe('igår')
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($beforeYesterday->text)->toBe('förrgår')
        ->and($beforeYesterday->start->get('year'))->toBe(2012)
        ->and($beforeYesterday->start->get('month'))->toBe(8)
        ->and($beforeYesterday->start->get('day'))->toBe(8)
        ->and($morning->text)->toBe('idag på morgonen')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->isCertain('hour'))->toBeFalse()
        ->and($forenoon->text)->toBe('idag på förmiddagen')
        ->and($forenoon->start->get('hour'))->toBe(9)
        ->and($midday->text)->toBe('idag på middagen')
        ->and($midday->start->get('hour'))->toBe(12)
        ->and($afternoon->text)->toBe('idag på eftermiddagen')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($evening->text)->toBe('idag på kvällen')
        ->and($evening->start->get('hour'))->toBe(20)
        ->and($night->text)->toBe('idag på natten')
        ->and($night->start->get('hour'))->toBe(2)
        ->and($midnight->text)->toBe('idag vid midnatt')
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('minute'))->toBe(0)
        ->and($midnight->start->get('second'))->toBe(0);
});
